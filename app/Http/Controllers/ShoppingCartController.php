<?php

namespace App\Http\Controllers;

use App\CustomTraits\CartInformation;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Session;

class ShoppingCartController extends Controller
{
    use CartInformation;

    /**
     * This will happen only on the menu page
     * @param $request Request the request to process
     */
    public function loadItemIntoShoppingCart(Request $request)
    {
        $item = MenuItem::find($request->input('menu_item_id'));
        $quantity_to_add = $request->input('quantity');
        // check if adding these items will cause the cart to be overfilled
        if ($quantity_to_add + $this->getCartQuantity() > $this->max_items_in_cart) {
            // TODO: as convenience to user, pass cart item quantity to js so that it can limit the user artifically and this will enforce
            return back()->with('status_bad',
                'Adding these items will cause the cart to contain too many items. Max number of items is ' . $this->max_items_in_cart);
        }
        // check that adding this new item will not put the cart
        // over the max restaurant limit
        if ($this->itemIsFromDifferentRestaurant($item->restaurant->id) &&
            $this->cartContainsMaxNumberOfRestaurants()
        ) {
            return back()->with('status_bad',
                'The selected item could not be added because the cart would have
                    contained items originating from four different restaurants. For a
                    given order, we can only deliver food from three different restaurants
                    at this time');
        }
        // get the instructions and extras
        $extras = [];
        $instructs = [];
        for ($i = 1; $i <= $quantity_to_add; $i++) {
            // extras_i is an array of the extras to add to one of the
            // menu_items, doesn't matter which one as long as it is
            // consistent with special_instructions_i
            $extras[] = $request->input('extras' . $i);
            $instructs[] = $request->input('special_instructions' . $i);
        }

        if ($this->cartHasItem($item->id)) {
            // instead of either appending or deleting an instruction/extra
            // just reset each field with this new request's info
            // and each field for this menu item will be updated properly
            $item_index = $this->getItemIndex($item->id);
            $this->addItemInstructions($item->id, $instructs, $item_index);
            $this->addCartItemQuantity($item->id, $quantity_to_add, $item_index);
            $this->addItemExtras($item->id, $extras, $item_index);

        } else {
            // item is not in the cart, so make it
            $new_item = [
                'menu_item_model' => $item,
                'quantity' => $quantity_to_add,
                'special_instructions' => $instructs,
                'extras' => $extras
            ];
            $cart = Session::get('cart', []);
            $cart[] = $new_item;
            Session::put('cart', $cart);
        }
        // make the message to the user
        if ($quantity_to_add == 1) {
            $add_item_message = "The item has been added to your cart!";
        } else {
            $add_item_message = "The items have been added to your cart!";
        }
        Session::flash('user_added_item', true);
        return back()->with('status_good', $add_item_message);
    }


    private
    function cartContainsMaxNumberOfRestaurants()
    {
        $cart = Session::get('cart');
        if (empty($cart)) return false;
        $restaurant_set = [];
        $num_restaurants = 0;
        foreach ($cart as $item) {
            $r_id = $item['menu_item_model']->restaurant->id;
            // if $item is from a unique restaurant
            if (!array_key_exists($r_id, $restaurant_set)) {
                $num_restaurants++;
                $restaurant_set[$r_id] = true;
            }
        }
        return $num_restaurants == $this->getMaxRestaurantsForOrder();
    }

    private
    function getMaxRestaurantsForOrder()
    {
        return 3;
    }
}
