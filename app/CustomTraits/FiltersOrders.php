<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/8/17
 * Time: 3:39 PM
 */

namespace App\CustomTraits;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;

trait FiltersOrders
{
    public function weeklySpecialOrders($orders)
    {
        return $this->getOrders($orders, RestaurantOrderCategory::WEEKLY_SPECIAL);
    }

    private function getOrders($orders, $of_type)
    {
        if (empty($orders)) {
            return [];
        }
        $orders_of_type = [];
        // make sure we are only using the orders that have on demand restaurants in them
        foreach ($orders as $any_order_type) {
            if ($any_order_type->hasOrderType($of_type)) {
                $orders_of_type[] = $any_order_type;
            }
        }
        return $orders_of_type;
    }

    public function pendingOnDemandOrders()
    {
        $pending_orders = Order::pending()->get();
        return $this->onDemandOrders($pending_orders);
    }

    public function onDemandOrders($orders)
    {
        return $this->getOrders($orders, RestaurantOrderCategory::ON_DEMAND);
    }

    public function ordersOfCourierType($orders, $of_courier_type)
    {
        if (empty($orders)) {
            return [];
        }
        $orders_of_courier_type = [];
        // make sure we are only using the orders that have on demand restaurants in them
        foreach ($orders as $any_order_type) {
            if ($any_order_type->hasCourierType($of_courier_type)) {
                $orders_of_courier_type[] = $any_order_type;
            }
        }
        return $orders_of_courier_type;
    }
}