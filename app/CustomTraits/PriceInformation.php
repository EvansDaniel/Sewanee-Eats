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
        $price_before_fees = $this->foodCostOfNonSpecialItems() + $this->foodCostOfSpecialItems();
        $fees = $this->fees();
        $price_of_fees = $fees['special'] + $fees['n_special'];
        return round(($price_of_fees + $price_before_fees), 2);
    }

    public function foodCostOfNonSpecialItems()
    {
        $items = $this->categorizedItems();
        return $this->getItemsCost($items['non_special_items']);
    }

    public function getItemsCost($items)
    {
        if (empty($items)) {
            return 0;
        }
        $price = 0;
        // used to keep track of already looked up accessories
        $acc_price_map = [];
        foreach ($items as $cart_item) {
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

    public function foodCostOfSpecialItems()
    {
        $items = $this->categorizedItems();
        return $this->getItemsCost($items['special_items']);
    }

    public function fees()
    {
        $special_items_fees = 0;
        $non_special_items_fees = 0;
        $items = $this->categorizedItems(true);
        if (!empty($items['special_items'])) {
            $special_items_fees = $this->getSpecialItemsFees($items['special_items']);
        }
        if (!empty($items['non_special_items'])) {
            $rest_loc_fee = $this->restaurantLocationCostFromCart($items['non_special_items']);
            $q_cost = $this->quantityCost(count($items['non_special_items']));
            $non_special_items_fees = $rest_loc_fee + $q_cost + $this->getBaseFee();
        }
        return [
            'special' => $special_items_fees,
            'n_special' => $non_special_items_fees
        ];
    }

    public function getSpecialItemsFees($s_items)
    {
        // + 1 dollar markup for each item and 3 dollar delivery fee
        $num_items = count($s_items);
        return /*$num_items + */
            max(
                $this->percentOfNumWeeklySpecialItems() * $num_items,
                $this->weeklySpecialBaseFee()
            );
    }

    private function percentOfNumWeeklySpecialItems()
    {
        return .75;
    }

    private function weeklySpecialBaseFee()
    {
        return 3;
    }

    public function restaurantLocationCostFromCart($n_s_items)
    {
        return $this->restaurantCost($n_s_items);
    }

    public function restaurantCost($n_s_items)
    {
        // determine farthest restaurant
        // return farthest restaurant location multiplier * (2*num_restuarants)
        if ($n_s_items == null) return 0;
        $multiplier = 0;
        $curr_distance = 0;
        $num_restaurants = 0;
        $loc_info = $this->getLocInfo();
        foreach ($n_s_items as $menu_item) {
            $num_restaurants++;
            $loc = $menu_item['menu_item_model']->restaurant->location;
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

    public function getNonSpecialItemFees()
    {
        return 0;
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
}