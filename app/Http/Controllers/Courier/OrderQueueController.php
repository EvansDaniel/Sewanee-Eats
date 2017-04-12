<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\Courier\CourierInfo;
use App\CustomClasses\Courier\OrderQueue;
use App\CustomClasses\Delivery\ManageOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Auth;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Mail;

/**
 * TODO: Bullet proof DB accesses
 * Class OrderQueueController
 * @package App\Http\Controllers\Courier
 */
class OrderQueueController extends Controller
{
    protected $empty_next_order_msg;

    public function __construct()
    {
        $this->middleware('auth');
        $this->empty_next_order_msg = 'There are no orders pending at this time. Great work! 
                Chill out and we will send you an email when we next get another order';
    }

    public function managerShowOrdersQueue()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')) {

            return view('delivery.manager_admin_order_queue',
                compact('pending_orders'));
        } else {
            return back()->with('status_bad', 'You don\'t have the necessary privileges 
            to access that information');
        }
    }

    /**
     * Shows the courier the orders that are currently
     * undelivered and not begin processed starting from oldest
     * to newest and only the orders that fit his/her courier type
     */
    public function showOrdersQueue()
    {
        if (!Auth::user()->hasRole('courier')) {
            return redirect()->route('showAdminDashboard')->with('status_bad', 'Only couriers can view the courier\'s order queue');
        }
        try {
            // no need for findOrFail, auth is required for access
            $order_queue = new OrderQueue(User::find(Auth::id()));
        } catch (InvalidArgumentException $e) {
            // TODO: return a nice view with the given message
            return redirect()->route('courierShowSchedule')
                ->with('status_good', $e->getMessage());
        }

        if (empty($err_msg = $order_queue->validateCourier())) {
            $next_order = $order_queue->nextOrder();
            // check order return, if empty no orders pending
            if ($order_queue->hasOrders() && empty($next_order)) {
                return redirect()->route('courierShowSchedule')
                    ->with('status_good', 'There are no orders pending that can be service by you at this time');
            } else if (empty($next_order)) {
                return redirect()->route('courierShowSchedule')
                    ->with('status_good', $this->empty_next_order_msg);
            } else {
                $orders = $order_queue->getPendingOrdersForCourier();
                return view('delivery.order_queue',
                    compact('orders', 'next_order', 'order_queue'));
            }
        } else { // courier error
            return redirect()->route('courierShowSchedule')
                ->with('status_good', $err_msg);
        }
    }

    public function cancelOrderDelivery(Order $order)
    {
        $user = Auth::user();
        $order = $order->find($user->courierInfo->current_order_id);
        // send email to manager that the courier cancelled the order delivery
        $this->sendOrderDeliveryCancellationEmail($user, $order);
        // mark courier as not delivering an order
        $courier_info = new CourierInfo($user);
        $courier_info->setIsDeliveringOrder(false);
        // remove the assigned courier from order and his/her payment for order
        $order_manager = new ManageOrder($order);
        $order_manager->removeAssignedCourier();
        // reinsert order into the queue
        OrderQueue::reinsertOrder($order);
        return redirect()->route('courierShowSchedule')->with('status_good', 'An email has been sent to the on shift manager informing
            him/her of the situation and should contact you shortly.');
    }

    public function sendOrderDeliveryCancellationEmail($user, $order)
    {
        if (empty($user) || empty($order)) {
            throw new InvalidArgumentException('$user or $order is null, neither can be null');
        }
        $diff_in_minutes = Carbon::now()->diffInMinutes($order->created_at);
        Mail::send('emails.order_delivery_cancellation', compact('user', 'order', 'diff_in_minutes'), function ($message) use ($user) {
            $message->from('sewaneeeats@gmail.com');
            $message->to('sewaneeeats@gmail.com')->subject($user->name . ' has cancelled the delivery of his/her order');
        });
    }

    // TODO: notify the assigned courier that an order has been cancelled or refunded

    public function currentOrder()
    {
        // the current order for this courier is the order
        // that has been most recently added to the couriers_order
        // listing and contains his/her user id
        $user = Auth::user();
        $curr_order = Order::find($user->courierInfo->current_order_id);
        return view('delivery.curr_order',
            compact('curr_order'));
    }

    public function markAsDelivered()
    {
        $user = Auth::user();
        $order = Order::find($user->courierInfo->current_order_id);
        $order_manager = new ManageOrder($order);
        // no longer processing and is delivered
        $order_manager->processingStatus(false);
        $order_manager->deliveredStatus(true);
        // set courier who is delivering to not be delivering anymore
        $courier_info = new CourierInfo($user);
        $courier_info->setIsDeliveringOrder(false);
        // redirect them to the next order in the queue
        return redirect()->route('nextOrderInQueue');
    }

    public function nextOrderInQueue()
    {
        if (!Auth::user()->hasRole('courier')) {
            return redirect()->route('showAdminDashboard')->with('status_bad', 'Only couriers can access the next order in the on demand queue');
        }
        try {
            // validity of courier checked in the constructor
            $order_queue = new OrderQueue(Auth::user());
        } catch (InvalidArgumentException $e) {
            // if user is delivering order, redirect to currentOrder with msg
            if (Auth::user()->courierInfo->is_delivering_order) {
                return redirect()->route('currentOrder')->with('status_bad', $e->getMessage());
            }
            // courier might not be on shift, or some other reason so redirect to schedule
            // with error message
            return redirect()->route('courierShowSchedule')
                ->with('status_good', $e->getMessage());
        }

        $next_order = $order_queue->nextOrder();
        if (empty($err_msg = $order_queue->validateCourier())) {
            // check order return, if empty no orders pending
            // pending orders now include ones that haven't been paid for
            if (empty($next_order)) {
                // no order pending so redirect to schedule with msg
                return redirect()->route('courierShowSchedule')
                    ->with('status_good', $this->empty_next_order_msg);
            } else {
                // valid courier and there is at least one pending order
                // assign next to courier and show message
                $order_manager = new ManageOrder($next_order);
                $order_manager->assignToOrder(Auth::user());
                \Session::flash('status_good',
                    'Woop woop! Here is your next order!');
                // redirect to the current order route
                return redirect()->route('currentOrder');
            }
        } else { // courier error
            return $err_msg;
        }
    }
}
