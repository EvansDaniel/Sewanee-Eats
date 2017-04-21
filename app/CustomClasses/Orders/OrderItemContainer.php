<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/21/17
 * Time: 9:52 AM
 */

namespace App\CustomClasses\Orders;


use App\CustomClasses\ShoppingCart\SpecialBilling;

class OrderItemContainer
{
    private $order_items_mappings;
    private $all_items;

    public function __construct($order_items_mappings)
    {

        $this->order_items_mappings = $order_items_mappings;
    }

    public function getItemOrderMapping()
    {
        return $this->order_items_mappings;
    }

    public function getEstimatedCost()
    {
        $cost = 0;
        $special_billing = new SpecialBilling();
        foreach ($this->getAllItems() as $item) {
            // TODO: save the amount of markup for the weekly special in the database since it is subject to change
            $cost += (($item->item->price - $special_billing->getMarkup()) * $this->getCount($item->menu_item_id));
            foreach ($item->accessories as $acc) {
                $cost += $acc->price;
            }
        }
        return $cost;
    }

    public function getAllItems()
    {
        if (!empty($this->all_items)) {
            return $this->all_items;
        }
        $items = [];
        $item_ids = [];
        foreach ($this->order_items_mappings as $items_mapping) {
            foreach ($items_mapping->getMenuItemOrders() as $item_order) {
                if (!in_array($item_order->menu_item_id, $item_ids)) {
                    $items[] = $item_order;
                    $item_ids[] = $item_order->menu_item_id;
                }
            }
        }
        return $this->all_items = $items;
    }

    public function getCount($item_id)
    {
        $total = 0;
        foreach ($this->order_items_mappings as $items_mapping) {
            foreach ($items_mapping->getMenuItemOrders() as $item) {
                if ($item->menu_item_id == $item_id) {
                    $total++;
                }
            }
        }
        return $total;
    }

    public function __toString()
    {
        $str = "";
        foreach ($this->order_items_mappings as $items_mapping) {
            $str .= $items_mapping->__toString();
        }
        return $str;
    }
}