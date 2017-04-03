<?php

namespace App\Http\Controllers;

use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomTraits\CartInformation;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function loadItemIntoShoppingCart(Request $request)
    {
        $item_id = $request->input('item_id');
        $item_type = $request->input('item_type');
        $cart = new ShoppingCart();
        $cart_items = [];
        for ($i = 1; $i <= $request->input('quantity'); $i++) {
            $cart_item = new CartItem($item_id, $item_type);
            $cart_items[] = $cart_item;
            $extra = $request->input('extras' . $i);
            $instruct = $request->input('special_instructions' . $i);
            $cart_item->setInstructions($instruct);
            $cart_item->setExtras($extra);
        }
        if ($cart->hasOnDemandOverflow($cart_items)) {
            return back()->with('status_bad',
                'Unable to add item to the cart because you have added the max number of on demand items for this order ' . $cart->getMaxOnDemandItems());
        }
        $error_val = $cart->putItems($cart_items);
        if ($error_val == -3) { // tried to add item with too many diff restaurants
            return back()->with('status_bad', 'Adding the item would cause you to order from too many different on demand restaurants. The max is two different on demand restaurants.');
        }
        if ($error_val == -1) {
            // on demand overflow
        }
        return back()->with('status_good', 'Item added to the cart');
    }
}
