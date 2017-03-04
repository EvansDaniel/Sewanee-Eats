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
        // TODO: here
    }
}
