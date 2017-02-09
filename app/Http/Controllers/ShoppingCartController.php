<?php

namespace App\Http\Controllers;

use App\CustomTraits\CartInformation;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Session;

class ShoppingCartController extends Controller
{
    use CartInformation;

    public function addToShoppingCart(Request $request)
    {
        $item = MenuItem::find($request->input('menu_item_id'));

        // extras includes free and non-free extras
        $quantity_to_add = $request->input('quantity');
        $extras = [];
        $sia = [];
        for ($i = 1; $i <= $quantity_to_add; $i++) {
            $extras[] = $request->input('extras' . $i);
            $sia[] = $request->input('special_instructions' . $i);
        }
        if ($this->cartHasItem($item->id)) {
            $cart = Session::get('cart');
            // get the item from the cart
            // protected from return value of negative one b/c of the if statement
            $cart_item_index = $this->getItemIndex($item->id);
            if ($cart_item_index == -1) { // this item was not found
                return back();
            }
            // if adding more items to cart puts the cart of its limit
            // of $this->max_items_in_cart
            // TODO: change this placement, check after adding new quantity
            if (!$this->cartHasValidNumberOfItems()) {
                return back()->with
                ('status_bad',
                    $this->maxItemInCartError($item->name));
            }
            // get the new quantity of this item and save it to the new cart
            $newQuantity = $cart[$cart_item_index]['quantity'] + $quantity_to_add;
            $cart[$cart_item_index]['quantity'] = $newQuantity;
            // Overwrite the old instructions if new $si is not empty
            if (!(empty($sia)) && count($sia) != 0) {
                foreach ($sia as $si) {
                    $cart[$cart_item_index]['special_instructions'][] = $si;
                }
            }
            // save the update cart to the session
            Session::put('cart', $cart);
        } else {
            // item doesn't exist, so create it and add it to the cart
            $product = [
                'menu_item_model' => $item,
                'quantity' => $quantity_to_add,
                'special_instructions' => $sia
            ];
            Session::put('cart',
                array_prepend($cart = Session::get('cart', []), $product));
        }
        // go back to restaurant menu page with a flashed link taking
        // the usr to his/her checkout page
        return back()->with('status_good', $item->name .
            " has been added to your cart!");
    }

    public function updateCart(Request $request)
    {
        $cart = Session::get('cart');
        if (empty($cart)) // extra sanity check
            return back();

        // if new q = 0, remove everything from cart
        // else update the q and the special instructions (IF ANY)
        $quantity = $request->input('quantity');
        $special_instructions = $request->input('special_instructions');
        $cart_item_id = $request->input('cart_item_id');
        // get index of item in cart session array
        $item_index = $this->getItemIndex($cart_item_id);
        if ($item_index == -1) { // this item was not found
            return back();
        }
        // user might not have updated special instructions
        if (!(empty($special_instructions)))
            $cart[$item_index]['special_instructions'] = $special_instructions;
        if ($quantity == 0) {
            // completely remove item from cart
            unset($cart[$item_index]);
            // save updated cart back to session
            Session::put('cart', $cart);
            return back()->with('status_good', 'Item deleted from cart!');
        } else { // update the quantity of the item in the cart

            // check we are not over $this->max_items_in_cart item limit
            // update sets the value of quantity, so we need to exclude
            // the clicked item's quantity from the sum
            $total_quantity = $quantity +
                $this->getCartQuantity($cart[$item_index]['menu_item_model']->id);
            if ($total_quantity > $this->max_items_in_cart) {
                return back()->with
                ('status_bad', $cart[$item_index]['menu_item_model']->name
                    . ' could not be updated. You can have a max of ' . $this->max_items_in_cart . ' items in the cart.');
            }
            $cart[$item_index]['quantity'] = $quantity;
        }
        // save updated cart back to session
        Session::put('cart', $cart);
        return back()->with('status_good', $cart[$item_index]['menu_item_model']->name .
            " has been updated!");
    }
}
