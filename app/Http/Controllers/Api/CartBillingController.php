<?php

namespace App\Http\Controllers\Api;

use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\Controller;

class CartBillingController extends Controller
{
    public function getPriceSummary()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        $price_summary = [
            'subtotal' => $bill->getSubtotal(),
            'total_price' => $bill->getTotal(),
            'cost_of_food' => $bill->costOfFood(),
            'delivery_fee' => $bill->getDeliveryFee(),
            'discount' => $bill->getDiscount()
        ];
        return response(json_encode($price_summary), 200);
    }
}
