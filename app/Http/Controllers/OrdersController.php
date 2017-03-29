<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

// TODO: add order change logic to this controller
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

    public function inputExtraOrder()
    {
        // TODO: make it so that a manager/admin can add order not filled on site
    }

    public function undoCloseVenmoOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        $order->is_open_order = false;
        // is this needed???
        $order->is_delivered = false;
        $order->save();
    }

    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $order = Order::find($order_id);
        \Log::info('here i am');
        $order->is_cancelled = 1;
        $order->save();
        return back()->with('status_good', 'Order Cancelled');
    }

    public function listWeeklyOrders()
    {
        $orders = Order::where([['is_weekly_special', 1], ['is_open_order', 0], ['is_cancelled', 0]])->get();
        return view('admin.order.list_weekly_orders', compact('orders'));
    }
}
