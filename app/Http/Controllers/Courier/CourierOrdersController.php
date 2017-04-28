<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;

class CourierOrdersController extends Controller
{
    public function showCurrentOrders()
    {
        $courier = \Auth::user();
        return view('employee.orders.current_orders_by_orders', compact('courier'));
    }

    public function showPastDeliveredOrders()
    {

    }

}
