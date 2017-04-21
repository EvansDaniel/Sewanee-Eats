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
    private static $letters;
    private $order_items_mappings;
    private $all_items;

    public function __construct($order_items_mappings)
    {
        $this->genAlphabetCap();
        $this->order_items_mappings = $order_items_mappings;
        $this->sortItemMappings();
    }

    private function genAlphabetCap()
    {
        for ($i = 65; $i <= 90; $i++) { // Ascii captials: A - Z
            OrderItemContainer::$letters[] = chr($i);
        }
    }

    private function sortItemMappings()
    {
        // returns a A -> Z ordering
        usort($this->order_items_mappings, function ($map_a, $map_b) {
            return strcmp($map_a->getOrder()->c_name, $map_b->getOrder()->c_name);
        });
    }

    public static function getLetters()
    {
        return OrderItemContainer::$letters;
    }

    public function getOrdersByFirstNameLetters($start_letter, $end_letter)
    {
        if (empty($start_letter) || empty($end_letter)) {
            return;
        }
        if (ord($start_letter) > ord($end_letter)) { // swap if in opposite order
            $temp = $start_letter;
            $start_letter = $end_letter;
            $end_letter = $temp;
        }
        $new_order_items_mapping = [];
        foreach ($this->order_items_mappings as $items_mapping) {
            $name = $items_mapping->getOrder()->c_name;
            // names are guaranteed to not be empty
            if ($this->charsBetween($name[0], $start_letter, $end_letter)) {
                $new_order_items_mapping[] = $items_mapping;
            }
        }
        $this->order_items_mappings = $new_order_items_mapping;
    }

    private function charsBetween($char, $start_char, $end_char)
    {
        $char = strtoupper($char);
        $start_char = strtoupper($start_char);
        $end_char = strtoupper($end_char);
        return ord($char) >= ord($start_char) && ord($char) <= ord($end_char);
    }

    public function getItemOrderMapping()
    {
        return $this->order_items_mappings;
    }

    public function getEstimatedCost()
    {
        $tax = 1.0925; // TODO: make this more general
        $cost = 0;
        $special_billing = new SpecialBilling();
        foreach ($this->getAllItems() as $item) {
            // TODO: save the amount of markup for the weekly special in the database since it is subject to change
            $cost += (($item->item->price - $special_billing->getMarkup()) * $this->getCount($item->menu_item_id)) * $tax;
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