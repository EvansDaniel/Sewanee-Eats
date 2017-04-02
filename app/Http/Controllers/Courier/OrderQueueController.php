<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\Courier\CourierInfo;
use App\CustomClasses\Courier\OrderQueue;
use App\CustomClasses\Delivery\ManageOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Auth;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class OrderQueueController extends Controller
{
    protected $empty_next_order_msg;

    public function __construct()
    {
        $this->middleware('auth');
        $this->empty_next_order_msg = 'There are no orders pending at this time';
    }

    public function managerShowOrdersQueue()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')) {
            $pending_orders = OrderQueue::getOrderQueue();

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
        try {
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
        $courier_info = new CourierInfo($user);
        $courier_info->setIsDeliveringOrder(false);
        return redirect()->route('nextOrderInQueue');
    }

    public function nextOrderInQueue()
    {
        try {
            // validity of courier checked in the constructor
            $order_queue = new OrderQueue(Auth::user());
        } catch (InvalidArgumentException $e) {
            return redirect()->route('courierShowSchedule')
                ->with('status_good', $e->getMessage());
        }
        $next_order = $order_queue->nextOrder();
        if (empty($err_msg = $order_queue->validateCourier())) {
            // check order return, if empty no orders pending
            if (empty($next_order)) {
                \Log::info($next_order . ' ' . ' here');
                return redirect()->route('courierShowSchedule')
                    ->with('status_good', $this->empty_next_order_msg);
            } else {

                // return deets of next order in queue
                $order_manager = new ManageOrder($next_order);
                $order_manager->assignToOrder(Auth::user());
                \Session::flash('status_good',
                    'You have now been assigned to this order');
                return view('delivery.next_order',
                    compact('next_order', 'order_queue'));
            }
        } else { // courier error
            return $err_msg;
        }
    }
}
