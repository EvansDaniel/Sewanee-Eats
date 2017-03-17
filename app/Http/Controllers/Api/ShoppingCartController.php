<?php

namespace App\Http\Controllers\Api;

use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function quantity()
    {
        $cart = new ShoppingCart();
        return json_encode($cart->quantity());
    }

    public function updateInstructions($cart_item_id, Request $request)
    {
        $cart = new ShoppingCart();
        $cart->updateInstructions($cart_item_id, $request->input('special_instructions'));
    }

    public function toggleExtras($cart_item_id, Request $request)
    {
        $cart = new ShoppingCart();
        $cart->toggleExtras($cart_item_id, $request->input('accessory'));
    }

    public function deleteFromCart($cart_item_id)
    {
        $cart = new ShoppingCart();
        $cart->deleteItem($cart_item_id);
    }
}
