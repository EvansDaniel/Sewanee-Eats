<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/12/17
 * Time: 12:36 AM
 */

namespace App\CustomClasses\Stats;

use App\Models\Order;

class OrderStats extends Stats
{
    protected $stats;

    public function __construct()
    {
        parent::__construct();
        $this->computeStats();
    }

    public function computeStats()
    {
        //$stat  = new Stat('hello',1);
        $this->stats = [
            new Stat('Total Number of Orders', $this->countOrders()),
            new Stat('Total Profit', $this->totalProfit()),
            new Stat('# of Unique Users', $this->numberOfUsers()),
            new Stat('Total Food Sales', $this->totalFoodSales()),
            new Stat('Favorite Restaurant', $this->favoriteRestaurant())
        ];
    }

    public function countOrders()
    {
        return count(Order::countable()->get());
    }

    public function totalProfit()
    {
        $profit = 0;
        $orders = Order::countable()->get();
        foreach ($orders as $order) {
            $profit += $order->orderPriceInfo->profit;
        }
        return '$' . number_format((float)$profit, 2, '.', '');
    }

    public function numberOfUsers()
    {
        $orders = Order::countable()->get();
        $emails = [];
        $num_unique_users = 0;
        foreach ($orders as $order) {
            if (!in_array($order->email_of_customer, $emails)) {
                $emails[] = $order->email_of_customer;
                $num_unique_users++;
            }
        }
        return $num_unique_users;
    }

    public function totalFoodSales()
    {
        $food_sales = 0;
        $orders = Order::countable()->get();
        foreach ($orders as $order) {
            $food_sales += $order->orderPriceInfo->cost_of_food;
        }
        return '$' . number_format((float)$food_sales, 2, '.', '');;
    }

    public function favoriteRestaurant()
    {
        $delivered_orders = Order::delivered()->get();
        $rests = [];
        $max = -1;
        $max_name = null;
        foreach ($delivered_orders as $order) {
            foreach ($order->menuItemOrders as $menu_item_order) {
                $name = $menu_item_order->item->restaurant->name;
                if (!isset($rests[$name])) {
                    $rests[$name] = 0;
                }
                $rests[$name]++;
                if ($rests[$name] > $max) {
                    $max = $rests[$name];
                    $max_name = $name;
                }
            }
        }
        return $max_name;
    }
}