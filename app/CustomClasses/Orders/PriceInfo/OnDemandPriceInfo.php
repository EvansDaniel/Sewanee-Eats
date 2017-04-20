<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/19/17
 * Time: 4:38 PM
 */

namespace App\CustomClasses\Orders\PriceInfo;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;

class OnDemandPriceInfo
{
    public function __construct()
    {

    }

    public static function onDemandDeliveredOrders()
    {
        $delivered_orders = Order::undelivered()->get();
        $on_demand_delivered_orders = [];
        foreach ($delivered_orders as $delivered_order) {
            if ($delivered_order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
                $on_demand_delivered_orders[] = $delivered_order;
            }
        }
        return $on_demand_delivered_orders;
    }
}