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
     */
    public function loadItemIntoShoppingCart(Request $request)
    {
        $item = MenuItem::find($request->input('menu_item_id'));
        $quantity_to_add = $request->input('quantity');
        $extras = [];
        $instructs = [];
        for ($i = 0; $i < $quantity_to_add; $i++) {
            // extras_i is an array of the extras to add to one of the
            // menu_items, doesn't matter which one as long as it is
            // consistent with special_instructions_i
            $extras[] = $request->input('extras' . $i);
            $instructs[] = $request->input('special_instructions' . $i);
        }
        if ($this->cartHasItem($item->id)) {// currently assuming this is working

            // instead of either appending or deleting an instruction/extra
            // just reset each field with this new request's info
            // and each field for this menu item will be updated properly
            $this->setItemInstructions($item->id, $instructs);
            $this->setCartItemQuantity($item->id, $quantity_to_add);
            $this->setItemExtras($item->id, $extras);
        } else { // item is not in the cart

            // check that adding this new item will not put the cart
            // over the max restaurant limit
            if ($this->itemIsFromDifferentRestaurant($item->id) &&
                $this->cartContainsMaxNumberOfRestaurants()
            ) {
                return back()->with('status_bad',
                    'The selected item could not be added because the cart would have 
                    contained items originating from four different restaurants. For a
                    given order, we can only deliver food from three different restaurants
                    at this time');
            }
            $new_item = [
                'menu_item_model' => $item,
                'quantity' => $quantity_to_add,
                'special_instructions' => $instructs,
                'extras' => $extras
            ];
            Session::put('cart',
                array_prepend($cart = Session::get('cart', []), $new_item));
        }
        // TODO: flash a link to th checkout page
        return back()->with('status_good', $item->name .
            " has been added to your cart!");
    }

    private function setItemInstructions($id, $instructions)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return null;
        }
        $cart[$item_index]['special_instructions'] = $instructions;
        Session::put('cart', $cart);
    }

    private function setCartItemQuantity($id, $quantity)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return;
        }
        $cart[$item_index]['quantity'] = $quantity;
        Session::put('cart', $cart);
    }

    private function setItemExtras($id, $extras)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return null;
        }
        $cart[$item_index]['extras'] = $extras;
        Session::put('cart', $cart);
    }

    private function itemIsFromDifferentRestaurant($id)
    {
        $cart = Session::get('cart');
        foreach ($cart as $item) {
            if ($item['menu_item_model']->id == $id) {
                return false;
            }
        }
        return true;
    }

    private function cartContainsMaxNumberOfRestaurants()
    {
        $cart = Session::get('cart');
        $restaurant_set = [];
        $num_restaurants = 0;
        foreach ($cart as $item) {
            $r_id = $item->restaurant->id;
            // if $item is from a unique restaurant
            if (!array_key_exists($r_id, $restaurant_set)) {
                $num_restaurants++;
                $restaurant_map[$r_id] = true;
            }
        }
        return $num_restaurants == $this->getMaxRestaurantsForOrder();
    }

    private function getMaxRestaurantsForOrder()
    {
        return 3;
    }

    /**
     * This will happen at the checkout/cart page
     */
    public function updateCartItem(Request $request)
    {
        $cart = Session::get('cart');
        if (empty($cart)) { // extra sanity check
            return back();
        }
        $item = MenuItem::find($request->input('menu_item_id'));
        $quantity_to_add = $request->input('quantity');
        $extras = [];
        $instructs = [];
        for ($i = 0; $i < $quantity_to_add; $i++) {
            // extras_i is an array of the extras to add to one of the
            // menu_items, doesn't matter which one as long as it is
            // consistent with special_instructions_i
            $extras[] = $request->input('extras' . $i);
            $instructs[] = $request->input('special_instructions' . $i);
        }
        // instead of either appending or deleting an instruction/extra
        // just reset each field with this new request's info
        // and each field for this menu item will be updated properly
        $this->setItemInstructions($item->id, $instructs);
        $this->setCartItemQuantity($item->id, $quantity_to_add);
        $this->setItemExtras($item->id, $extras);
        return back()->with('status_good',
            $item->name . ' updated successfully');
    }

    public function getItemAccessories($id)
    {
        return MenuItem::find($id)->accessories;
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

    public function getCartItem($id)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return null;
        }
        return json_encode($cart[$item_index]);
    }

    public function getItemExtras($id)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return null;
        }
        return $cart[$item_index]['extras'];
    }

    private function getMenuItemModel($id)
    {
        $cart = Session::get('cart');
        if ($this->cartHasItem($id)) {
            $item_index = $this->getItemIndex($id);
            return $cart[$item_index]['menu_item_model'];
        }
        return null;
    }

    private function getItemInstructions($id)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return null;
        }
        return $cart[$item_index]['special_instructions'];
    }

    private function getCartItemQuantity($id)
    {
        $cart = Session::get('cart');
        if ($item_index = $this->getItemIndex($id) == -1) {
            return 0;
        }
        return $cart[$item_index]['quantity'];
    }
}
