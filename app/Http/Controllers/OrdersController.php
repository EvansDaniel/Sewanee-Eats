<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function closeVenmoOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order->is_open_order = false;
        $order->save();
        return back()->with('status_good', 'Order payment confirmed');
    }

    public function undoCloseVenmoOrder(Request $request)
    {

    }

    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        \Log::info('here i am');
        $order->is_cancelled = 1;
        return back()->with('status_good', 'Order Cancelled');
    }

    public function listWeeklyOrders()
    {
        $orders = Order::where([['is_weekly_special', 1], ['is_open_order', false], ['is_cancelled', false]])->get();
        return view('admin.order.list_weekly_orders', compact('orders'));
    }
}
