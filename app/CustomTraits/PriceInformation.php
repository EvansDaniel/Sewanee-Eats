<?php

namespace App\CustomTraits;

use Session;

trait PriceInformation
{
    public function getTotalPrice($location)
    {
        return $this->getSubTotal($location) * $this->getStateTax();
    }

    /**
     * @param $location
     * @return int price after fees
     */
    public function getSubTotal($location)
    {
        $price_before_fees = $this->getPriceBeforeFees();
        $location_cost = $this->getLocationCost($price_before_fees, $location);
        $fees_total = $location_cost + $this->getBaseFee();
        if ($fees_total > 15) {
            $fees_total = 15;
            return round(($fees_total + $price_before_fees), 2);
        }
        return round(($fees_total + $price_before_fees), 2);
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

    public function getLocationCost($price_before_fees, $location)
    {
        /*if($location != 'campus' || $location != 'downtown') {
            die('Invalid index passed to $location parameter in getLocationCost(): '. $location);
        }*/
        return $this->getLocationMultiplier()[$location] * $price_before_fees;
    }

    public function getLocationMultiplier()
    {
        return [
            'campus' => .2,
            'downtown' => .4
        ];
    }

    public function getBaseFee()
    {
        return 3;
    }

    public function getStateTax()
    {
        return 1.0925;
    }
}