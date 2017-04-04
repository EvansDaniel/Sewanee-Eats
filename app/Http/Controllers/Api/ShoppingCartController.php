<?php

namespace App\Http\Controllers\Api;

use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * JS Endpoint for updating cart information on the checkout page
 * Class ShoppingCartController
 * @package App\Http\Controllers\Api
 */

class ShoppingCartController extends Controller
{
    public function quantity()
    {
        $cart = new ShoppingCart();
        return json_encode($cart->getQuantity());
    }

    public function updateInstructions($cart_item_id, Request $request)
    {
        $cart = new ShoppingCart();
        $cart->updateInstructions($cart_item_id, $request->input('special_instructions'));
    }

    public function toggleExtra($cart_item_id, Request $request)
    {
        $cart = new ShoppingCart();
        $cart->toggleExtra($cart_item_id, $request->input('accessory'));
    }

    public function deleteFromCart($cart_item_id)
    {
        $cart = new ShoppingCart();
        $cart->deleteItem($cart_item_id);
    }
}
