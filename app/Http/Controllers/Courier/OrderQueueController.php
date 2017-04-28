<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\Courier\OrderQueue;
use App\CustomClasses\Delivery\DeliveryInfo;
use App\CustomClasses\Delivery\ManageOrder;
use App\CustomClasses\Orders\RestaurantsOrdersCategorizer;
use App\Exceptions\InvalidUserTypeException;
use App\Exceptions\ResourceNotAvailableException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Auth;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
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
        parent::__construct();
        $this->middleware('auth');
        $this->empty_next_order_msg = 'There are no orders pending at this time. :)';
    }

    public function showQueuedOrder(Order $order, $order_id)
    {
        $order = $order->find($order_id);
        $rest_order_categorizer = new RestaurantsOrdersCategorizer([$order]);
        $interactive = false;
        return view('employee.orders.show_order',
            compact('rest_order_categorizer', 'order', 'interactive'));
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

            // the user passed is not a courier
        } catch (InvalidUserTypeException $e) {
            return back()->with('status_bad', $e->getMessage());

            // the courier is unavailable for some reason
        } catch (ResourceNotAvailableException $e) {
            return back()->with('status_bad', $e->getMessage());
        }
        // No need to call validate courier here
        // because the courier is validated by the OrderQueue constructor

        /*// if orders pending but no orders for this courier
        if ($order_queue->hasOrders() && !$order_queue->hasOrdersForCourierType()) {
            return redirect()->route('courierShowSchedule')
                ->with('status_good', 'There are no orders pending that can be service by you at this time');
            // there are no orders pending AT ALL
        } else if (!$order_queue->hasOrders()) {
            return redirect()->route('courierShowSchedule')
                ->with('status_good', $this->empty_next_order_msg);
        } else {*/
        // there are orders that can be serviced by this courier
        $courier = Auth::user();
        return view('delivery.order_queue',
            compact('order_queue', 'courier'));
        //}
    }

    public function assignCourierToOrder(Request $request, Order $order)
    {
        $order_id = $request->input('order_id');
        // get models
        $courier = Auth::user();
        $order = $order->find($order_id);
        //
        $order_manager = new ManageOrder($order);
        $order_manager->assignToOrder($courier);
        $order_manager->processingStatus(true);
        return back()->with('status_good', 'Order #' . $order_id . ' assigned in your current orders summary');
    }

    public function cancelOrderDelivery(Request $request, Order $order)
    {
        $order_id = $request->input('order_id');
        $user = Auth::user();
        $order_to_cancel = $order->find($order_id);
        // send email to manager that the courier cancelled the order delivery
        $this->sendOrderDeliveryCancellationEmail($user, $order_to_cancel);
        // remove the assigned courier from order and his/her payment for order
        $order_manager = new ManageOrder($order_to_cancel);
        $order_manager->removeAssignedCourier();
        // reinsert order into the queue
        OrderQueue::reinsertOrder($order_to_cancel);

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

    public function markAsDelivered(Request $request)
    {
        $user = Auth::user();
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order_manager = new ManageOrder($order);
        // remove current courier
        // it is important to remove the courier before
        // changing the delivered status
        $assigned_courier = $order_manager->removeAssignedCourier();
        // order no longer processing and is delivered
        // remove the currently assigne courier
        $order_manager->processingStatus(false);
        $order_manager->deliveredStatus(true);


        // Save the courier/order relationship for the courier who delivered this order
        $courier_payment = DeliveryInfo::getMaxRestaurantCourierPayment($order);
        $order->courier()->attach($assigned_courier->id,
            [
                'courier_payment' => $courier_payment,
                'time_to_complete_order' => Carbon::now()->diffInMinutes($order->created_at)
            ]);

        // redirect them to the next order in the queue
        if ($user->isDeliveringOrders()) {
            // redirect to courier's current orders
            return redirect()->route('showCurrentOrders')
                ->with('status_good', 'Order marked as delivered');
        }
        // redirect to pending orders
        return redirect()->route('showOrdersQueue')
            ->with('status_good', 'Order marked as delivered');
    }
}
