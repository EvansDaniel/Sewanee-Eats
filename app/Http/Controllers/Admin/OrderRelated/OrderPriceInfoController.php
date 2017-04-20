<?php

namespace App\Http\Controllers\Admin\OrderRelated;

use App\CustomClasses\Orders\PriceInfo\OnDemandPriceInfo;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderPriceInfoController extends Controller
{
    /**
     * This will show the amount made from JUST the on demand portion of each
     * order
     */
    public function showOnDemandPriceInfo()
    {
        $on_demand_delivered_orders = OnDemandPriceInfo::onDemandDeliveredOrders();
    }

    /**
     * This will show the amount made from the JUST the weekly specials
     * part of any order
     */
    public function showSpecialsPriceInfo()
    {

    }

    public function showOrderPriceInfo(Order $order)
    {
        $orders = $order->countable()->get();
        $order_calc = new OrderCalculation($orders);
        return view('admin.order.price_info.all_order_price_info',
            compact('orders', 'order_calc'));
    }
}
