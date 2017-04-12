<?php

namespace App\Http\Controllers;

use App\CustomClasses\Delivery\ManageOrder;
use App\CustomClasses\ShoppingCart\ItemLister;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;
use Auth;
use Carbon\Carbon;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;

// TODO: add order change logic to this controller
class OrdersController extends Controller
{
    public function viewOnDemandOpenOrders(Order $order)
    {
        $orders = Order::all()->sort(function ($a, $b) {
            $carbon_a = new Carbon($a->created_at);
            $carbon_b = new Carbon($b->created_at);
            return $carbon_a->lessThanOrEqualTo($carbon_b);
        });
        $on_demand_open_orders = [];
        foreach ($orders as $order) {
            if ($order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
                $on_demand_open_orders[] = $order;
            }
        }
        $venmo_payment_type = PaymentType::VENMO_PAYMENT;
        return view('admin.order.on_demand_orders', compact('on_demand_open_orders', 'venmo_payment_type'));
    }

    public function toggleOrderIsDelivered(Order $order,Request $request)
    {
        $order_id = $request->input('order_id');
        $order = $order->findOrFail($order_id);
        $order_manager = new ManageOrder($order);
        $order_manager->deliveredStatus(!$order->is_delivered);
        return back()->with('status_good', 'Order delivery confirmed as ' . (!$order->is_delivered ? " not delivered" : "delivered"));
    }

    public function togglePaymentConfirmationForVenmo(Order $order,Request $request)
    {
        $order_id = $request->input('order_id');
        $order = $order->find($order_id);
        $order_manager = new ManageOrder($order);
        if ($is_paid_for = $order->is_paid_for) {
            $order_manager->paidForStatus(false);
        } else {
            $order_manager->paidForStatus(true);
        }
        return back()->with("status_good", "Order payment status confirmed as " . (!$is_paid_for ? "not " : "" . "paid for"));
    }

    public function inputExtraOrder()
    {
        // TODO: make it so that a manager/admin can add order not filled on site
    }

    public function undoCloseVenmoOrder(Request $request)
    {

    }

    public function toggleRefundOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::findOrFail($order_id);
        $order_manager = new ManageOrder($order);
        try {
            if ($is_refunded = $order->was_refunded) { // undo refund
                $order_manager->refundOrder(false);
            } else { // refund
                $order_manager->refundOrder(true);
            }
        } catch (InvalidArgumentException $e) {
            return back()->with('status_bad', 'The order has been cancelled and thus can\'t be refunded');
        }
        return back()->with('status_good', 'Order refund status confirmed as ' . (!$is_refunded ? " not refunded" : "refunded"));
    }

    public function toggleOrderCancellation(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::findOrFail($order_id);
        $order_manager = new ManageOrder($order);
        if ($is_cancelled = $order->is_cancelled) { // undo order cancel
            $order_manager->cancellationStatus(false);
        } else { // cancel order
            $order_manager->cancellationStatus(true);
        }
        return back()->with("status_good", "Order confirmed as " . (!$is_cancelled ? "not cancelled" : "cancelled"));

    }

    // temporary thing
    public function showOpenOnDemandOrders()
    {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')) {
            $orders = Order::all();
            $pending_orders = [];
            foreach ($orders as $order) {
                if ($order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
                    $on_demand_open_orders[] = $order;
                }
            }
            return view('admin.order.temp_on_demand_orders',
                compact('pending_orders'));
        } else {
            return back()->with('status_bad', 'You don\'t have the necessary privileges 
            to access that information');
        }
    }

    public function listWeeklyOrders()
    {

    }

    public function orderSummaryForAdmin(Order $order, int $order_id)
    {
        $next_order = $order->findOrFail($order_id);
        $item_lister = new ItemLister($next_order);
        return view('admin.partials.order_summary_for_admin',
            compact('next_order', 'item_lister'));
    }
}
