<?php

namespace App\Http\Controllers;

use App\CustomClasses\Delivery\ManageOrder;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;

// TODO: add order change logic to this controller
class OrdersController extends Controller
{
    public function viewOnDemandOpenOrders()
    {
        $orders = Order::all();
        $on_demand_open_orders = [];
        foreach ($orders as $order) {
            if ($order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
                $on_demand_open_orders[] = $order;
            }
        }
        $venmo_payment_type = PaymentType::VENMO_PAYMENT;
        return view('admin.order.on_demand_orders', compact('on_demand_open_orders', 'venmo_payment_type'));
    }

    public function confirmPaymentForVenmo(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order_manager = new ManageOrder($order);
        $order_manager->paidForStatus(true);
        return back()->with("status_good", "Order has been paid for!!");
    }

    public function inputExtraOrder()
    {
        // TODO: make it so that a manager/admin can add order not filled on site
    }

    public function undoCloseVenmoOrder(Request $request)
    {

    }

    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order_manager = new ManageOrder($order);
        $order_manager->cancellationStatus(true);
        return back()->with("status_good", "Order has been cancelled!");

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
}
