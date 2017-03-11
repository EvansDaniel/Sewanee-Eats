<?php

namespace App\CustomTraits;

use App\Models\Accessory;
use Session;

trait PriceInformation
{
    use CartInformation;

    public function getPriceSummary()
    {
        $subtotal = $this->getSubTotal();
        // TODO: dynamically fill in location that gets passed to getTotalPrice
        $total_price = $this->getTotalPrice($subtotal);
        $items = $this->categorizedItems();
        $num_items = count($items['special_items']) + count($items['non_special_items']);
        // get the base fee for weekly specials
        $delivery_fee = $this->weeklySpecialBaseFee($num_items);
        // get the total cost of all the food in the cart
        $cost_of_food = $this->foodCostOfNonSpecialItems() + $this->foodCostOfSpecialItems();
        // get the percentage saved on the delivery fee
        $delivery_fee_percentage_saved = $this->deliveryFeePercentageSaved($num_items);
        return [
            'total_price' => $total_price,
            'subtotal' => $subtotal,
            'delivery_fee' => $delivery_fee,
            'cost_of_food' => $cost_of_food,
            'delivery_fee_percentage_saved' => $delivery_fee_percentage_saved
        ];
    }

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
        $c_items = $this->categorizedItems(false);
        $delivery_fee = $this->weeklySpecialBaseFee(count($c_items['special_items']) + count($c_items['non_special_items']));
        return round(($delivery_fee + $price_before_fees), 2);
    }

    public function foodCostOfNonSpecialItems()
    {
        $items = $this->categorizedItems();
        return $this->getItemsCost($items['non_special_items']);
    }

    public function deliveryFeePercentageSaved($num_items)
    {
        return (20 * ($num_items-1));
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

    /**
     * @return array an array containing the cost of fees for the weekly special items in the cart
     * and the cost of deliveriy for the OnDemand delivery items in the cart
     */
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

    /**
     * @param $s_items array the number of items in an order
     * @return float returns the gross profit made from this order
     */
    public function getSpecialItemsFees($s_items)
    {
        // + 1 dollar markup for each item and 3 dollar delivery fee
        $num_items = count($s_items);
        return ($this->moneyPerItem()*$num_items)
            + $this->weeklySpecialBaseFee($num_items);
    }

    private function moneyPerItem()
    {
        return .75;
    }

    private function weeklySpecialBaseFee($num_items)
    {
        // getBaseFee() dollars delivery fee for first item
        // for every item after that, save 60 cents on the delivery fee
        return ($this->getBaseFee() - ($num_items-1) * .60);
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