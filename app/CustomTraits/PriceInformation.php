<?php

namespace App\CustomTraits;

use Session;

trait PriceInformation
{

    public function getTotalPrice($location)
    {
        $price_before_fees = $this->getPriceBeforeFees();
        return $this->getLocationMultiplier()[$location] * $price_before_fees
            + $this->getBaseServiceFee();
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
        return $price;
    }

    public function getLocationMultiplier()
    {
        return [
            'campus' => 1.33,
            'downtown' => 1.55
        ];
    }

    public function getBaseServiceFee()
    {
        return 3;
    }
}