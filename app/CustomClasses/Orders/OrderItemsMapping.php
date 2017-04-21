<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/20/17
 * Time: 10:20 PM
 */

namespace App\CustomClasses\Orders;


use App\CustomClasses\ShoppingCart\PaymentType;
use App\Models\Order;

class OrderItemsMapping
{
    private static $payment_types;
    private $order;
    private $menu_item_orders;

    public function __construct(Order $order, $menu_item_orders)
    {

        $this->order = $order;
        $this->menu_item_orders = $menu_item_orders;
        OrderItemsMapping::$payment_types = [PaymentType::STRIPE_PAYMENT => "Card", PaymentType::VENMO_PAYMENT => "Venmo"];
    }

    public function __toString()
    {
        $str = $this->getOrder();
        $str .= " -> [";
        foreach ($this->getMenuItemOrders() as $item_order) {
            $str .= $item_order;
        }
        $str .= "]";
        return $str;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getMenuItemOrders()
    {
        return $this->menu_item_orders;
    }

    public function getPaidWith()
    {
        return OrderItemsMapping::$payment_types[$this->getOrder()->payment_type];
    }

}