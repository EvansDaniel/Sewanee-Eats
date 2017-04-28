<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/8/17
 * Time: 3:39 PM
 */

namespace App\CustomTraits;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\CourierRelated\Orders\CourierCurrentOrder;
use App\Models\Order;

trait FiltersOrders
{
    public function weeklySpecialOrders(array $orders)
    {
        return $this->getOrders($orders, RestaurantOrderCategory::WEEKLY_SPECIAL);
    }

    private function getOrders($orders, int $of_type)
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
        return $this->onDemandOrders($pending_orders->toArray());
    }

    public function onDemandOrders($orders)
    {
        return $this->getOrders($orders, RestaurantOrderCategory::ON_DEMAND);
    }

    public function filterInProcessOrders($orders)
    {
        $in_process_order_ids = CourierCurrentOrder::all(['order_id'])->toArray();
        if (count($in_process_order_ids) == 0)
            return $orders;
        $in_process_order_ids = $in_process_order_ids[0];
        $ret_orders = [];
        foreach ($orders as $order) {
            if (!in_array($order->id, $in_process_order_ids)) {
                $ret_orders[] = $order;
                $in_process_order_ids[] = $order->id;
            }
        }
        return $ret_orders;
    }

    public function ordersOfCourierType(array $orders, int $of_courier_type)
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