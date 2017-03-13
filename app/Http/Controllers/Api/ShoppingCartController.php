<?php

namespace App\Http\Controllers\Api;

use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\Controller;

class ShoppingCartController extends Controller
{
    public function quantity()
    {
        $cart = new ShoppingCart();
        return json_encode($cart->quantity());
    }
}
