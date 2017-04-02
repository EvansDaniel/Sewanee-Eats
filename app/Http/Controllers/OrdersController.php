<?php

namespace App\Http\Controllers;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;
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
        return view('order.on_demand_orders', compact('on_demand_open_orders'));
    }

    public function closeVenmoOrder(Request $request)
    {

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

    }

    public function listWeeklyOrders()
    {

    }
}
