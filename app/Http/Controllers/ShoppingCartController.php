<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Session;

class ShoppingCartController extends Controller
{
    public function showShoppingCart()
    {
        $cart = Session::get('cart');
        return view('shopping_cart', compact('cart'));
    }

    public function addToShoppingCart(Request $request)
    {
        $item = MenuItem::find($request->input('menu_item_id'));
        $si = $request->input('special_instructions');
        $quantity = $request->input('quantity');
        if ($this->item_exists_in_cart($item->id)) {
            $cart = Session::get('cart');
            // get the item from the cart
            // protected from return value of negative one b/c of the if statement
            $cart_item_index = $this->get_item_index($item->id);
            // get the new quantity of this item and save it to the new cart
            $newQuantity = $cart[$cart_item_index]['quantity'] + $quantity;
            $cart[$cart_item_index]['quantity'] = $newQuantity;
            // Overwrite the old instructions if new $si is not empty
            if (!empty($si)) {
                $cart[$cart_item_index]['special_instructions'] = $si;
            }
            // save the update cart to the session
            Session::put('cart', $cart);
        } else {
            // item doesn't exist, so create it and add it to the cart
            $product = [
                'menu_item_model' => $item,
                'quantity' => $quantity,
                'special_instructions' => $si
            ];
            Session::put('cart', array_prepend($cart = Session::get('cart', []), $product));
        }
        return back();
    }

    private function item_exists_in_cart($id)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return false;
        }

        foreach ($cart as $cart_item) {
            if ($cart_item['menu_item_model']->id == $id)
                return true;
        }
        return false;
    }

    /**
     * @param $id integer the id of a menu item
     * @return integer returns -1 if the item does not exist
     *                 Otherwise, it returns the index of the object into the
     *                 cart session array
     */
    private function get_item_index($id)
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return -1;
        }
        $size = count($cart);
        for ($i = 0; $i < $size; $i++) {
            if ($id == $cart[$i]['menu_item_model']->id) {
                return $i;
            }
        }
        return -1;
    }
}
