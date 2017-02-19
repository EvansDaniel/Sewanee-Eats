<?php

namespace App\CustomTraits;

use App\Models\Accessory;
use Session;

trait PriceInformation
{
    use CartInformation;

    public function getTotalPrice($subtotal = null)
    {
        if ($subtotal == null) $subtotal = $this->getSubTotal();
        return round($subtotal * $this->getStateTax(), 2);
    }

    /**
     * @return float price after fees
     */
    public function getSubTotal()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return 0;
        }
        $price_before_fees = $this->priceBeforeFeesFromCart();
        $fees = $this->fees();
        return round(($fees + $price_before_fees), 2);
    }

    public function priceBeforeFeesFromCart()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return null;
        }
        $price = 0;
        // used to keep track of already looked up accessories
        $acc_price_map = [];
        foreach ($cart as $cart_item) {
            $price += $cart_item['menu_item_model']->price
                * $cart_item['quantity'];
            foreach ($cart_item['extras'] as $item_extras) {
                // check if the user added extras for this item
                if (!empty($item_extras)) {
                    foreach ($item_extras as $acc_id) {
                        // look up accessory if we didn't already find it
                        if (!array_key_exists($acc_id, $acc_price_map)) {
                            $acc_price_map[$acc_id] = Accessory::find($acc_id)->price;
                        }
                        $price += $acc_price_map[$acc_id];
                    }
                }
            }
        }
        return round($price, 2);
    }

    public function fees()
    {
        $rest_loc_fee = $this->restaurantLocationCostFromCart();
        $q_cost = $this->quantityCost($this->getCartQuantity());
        $fees = $rest_loc_fee + $q_cost + $this->getBaseFee();
        if ($fees > 15) {
            return 15;
        }
        return $fees;
    }

    public function restaurantLocationCostFromCart()
    {
        $menu_items = $this->getMenuItems();
        return $this->restaurantCost($menu_items);
    }

    private function getMenuItems()
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return null;
        }
        $menu_items = [];
        foreach ($cart as $cart_item) {
            $menu_items[] = $cart_item['menu_item_model'];
        }
        return $menu_items;
    }

    public function restaurantCost($menu_items)
    {
        // determine farthest restaurant
        // return farthest restaurant location multiplier * (2*num_restuarants)
        if ($menu_items == null) return 0;
        $multiplier = 0;
        $curr_distance = 0;
        $num_restaurants = 0;
        $loc_info = $this->getLocInfo();
        foreach ($menu_items as $menu_item) {
            $num_restaurants++;
            $loc = $menu_item->restaurant->location;
            if ($loc_info[$loc]['distance'] > $curr_distance) {
                $multiplier = $loc_info[$loc]['multiplier'];
            }
        }
        return $multiplier * ($this->costPerRestaurant() * $num_restaurants);
    }

    private function getLocInfo()
    {
        return [
            'campus' => [
                'multiplier' => .2,
                'distance' => 0
            ],
            'downtown' => [
                'multiplier' => .4,
                'distance' => 1
            ],
            'monteagle' => [
                'multiplier' => .5,
                'distance' => 2
            ]
        ];
    }

    private function costPerRestaurant()
    {
        return 2;
    }

    public function quantityCost($num_items)
    {
        return $num_items >= 5 ? 3 : 1;
    }

    private function getBaseFee()
    {
        return 3;
    }

    private function getStateTax()
    {
        return 1.0925;
    }
}