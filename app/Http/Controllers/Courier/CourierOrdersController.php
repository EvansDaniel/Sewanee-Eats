<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;

class CourierOrdersController extends Controller
{
    public function showUndeliveredOrders()
    {
        // depending on the courier type of the logged in courier, we show
        // certain orders
    }

    public function orderDelivered()
    {
        // this is the method that will set an order as having been delivered
    }

    // TODO: extend with functionality to track orders???

}
