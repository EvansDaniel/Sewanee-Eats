<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/20/17
 * Time: 7:22 PM
 */

namespace App\CustomTraits;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;

trait CategorizeItems
{

    /**
     * Categorizes the items in the cart to three different
     * categories:
     * On Demand, Weekly Special, and Event
     * It will then set the corresponding instance variables
     * possibly to null, so make sure to check for it
     */
    public function categorizedItems()
    {
        $items = [
            'on_demand' => null,
            'weekly_special' => null,
            'event' => null
        ];
        if (!empty($this->cart)) {
            foreach ($this->cart as $cart_item) {
                if ($cart_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::ON_DEMAND) {
                    $items["on_demand"][] = $cart_item;
                } else if ($cart_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::WEEKLY_SPECIAL) {
                    $items["weekly_special"][] = $cart_item;
                } else if ($cart_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::EVENT) {
                    $items["event"][] = $cart_item;
                }
            }
        }
        $this->on_demand_items = $items['on_demand'];
        $this->weekly_special_items = $items["weekly_special"];
        $this->event_items = $items['event'];
    }

}