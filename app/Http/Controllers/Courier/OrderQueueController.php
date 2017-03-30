<?php

namespace App\Http\Controllers\Courier;

use App\CustomClasses\Courier\OrderQueue;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class OrderQueueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            return $e->getMessage();
        }

        if (empty($err_msg = $order_queue->validateCourier())) {
            $next_order = $order_queue->nextOrder();
            // check order return, if empty no orders pending
            if ($order_queue->hasOrders() && empty($next_order)) {
                return 'There are no orders pending that can be service by you at this time';
            } else {
                $orders = $order_queue->getPendingOrdersForCourier();
                return view('delivery.order_queue',
                    compact('orders', 'next_order', 'order_queue'));
            }
        } else { // courier error
            return $err_msg;
        }
    }

    public function nextOrderInQueue()
    {
        $order_queue = new OrderQueue(Auth::user());
        $next_order = $order_queue->nextOrder();
        /*foreach ($orders as $order) {
            $order->showCourierTypes();
        }*/
        if (empty($err_msg = $order_queue->validateCourier())) {
            // check order return, if empty no orders pending
            if (empty($next_order)) {
                return 'No orders to be serviced at this time';
            } else {
                return view('delivery.order_queue',
                    compact('orders', 'next_order'));
            }
        } else { // courier error
            return $err_msg;
        }
    }
}
