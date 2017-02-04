<?php

namespace App\CustomTraits;

use Session;

trait PriceInformation
{
    /**
     * @param $location
     * @return int price after fees
     */
    public function getTotalPrice($location)
    {
        $price_before_fees = $this->getPriceBeforeFees();
        $totalPrice = round($this->getLocationMultiplier()[$location] * $price_before_fees
            + $this->getBaseFee(), 2);
        if ($totalPrice > 15)
            return 15 + $price_before_fees;
    }

    public function getPriceBeforeFees()
    {
        $cart = Session::get('cart');
        if (empty($cart))
            return 0;
        $price = 0;
        foreach ($cart as $order) {
            $item = $order['menu_item_model'];
            $price += $item->price * $order['quantity'];
        }
        return round($price, 2);
    }

    /* public function getNumberOfItemsPrice($count_items_in_cart) {
         return $count_items_in_cart > 5 ? 1 : 2;
     }*/

    public function getLocationMultiplier()
    {
        return [
            'campus' => 1.2,
            'downtown' => 1.4
        ];
    }

    public function getBaseFee()
    {
        return 3;
    }
}