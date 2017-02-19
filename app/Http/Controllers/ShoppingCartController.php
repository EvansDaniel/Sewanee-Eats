<?php

namespace App\Http\Controllers;

use App\CustomTraits\CartInformation;
use App\Models\Accessory;
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


    // HELPERS -------------------------------------------------------------------


    /**
     * This will happen at the checkout/cart page
     */
    // TODO: fix this function
    private function addItemInstructions($id, $instructions, $item_index = -2)
    {
        $cart = Session::get('cart');
        if ($item_index < 0) {
            if (($item_index = $this->getItemIndex($id)) == -1) {
                return null; // element does not exist
            }
        }
        if (!empty($cart[$item_index]['special_instructions'])) {
            $cart[$item_index]['special_instructions'] =
                $this->cartArrayPush($cart[$item_index]['special_instructions'], $instructions);
        }
        Session::put('cart', $cart);
    }

    public function updateCartItem(Request $request)
    {
        $cart = Session::get('cart');
        if (empty($cart)) { // extra sanity check
            return back();
        }
        $item = MenuItem::find($request->input('cart_item_id'));
        $extras = Accessory::find($request->input('extras'));
        $instructs = $request->input('special_instructions');
        $this->addItemInstructions($item->id, $instructs);
        $this->addItemExtras($item->id, $extras);
        return back()->with('status_good',
            $item->name . ' updated successfully');
    }

    public
    function updateCart(Request $request)
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

    public
    function pr($x)
    {
        echo "<pre>";
        print_r($x);
        echo "</pre>";
    }

    public
    function getCartItem($id)
    {
        $cart = Session::get('cart');
        if (($item_index = $this->getItemIndex($id)) == -1) {
            return null;
        }
        return json_encode($cart[$item_index]);
    }


    public
    function getItemExtras($id)
    {
        $cart = Session::get('cart');
        if (($item_index = $this->getItemIndex($id)) == -1) {
            return null;
        }
        return $cart[$item_index]['extras'];
    }

    private
    function getMenuItemModel($id)
    {
        $cart = Session::get('cart');
        if ($this->cartHasItem($id)) {
            ($item_index = $this->getItemIndex($id));
            return $cart[$item_index]['menu_item_model'];
        }
        return null;
    }

    private
    function getItemInstructions($id)
    {
        $cart = Session::get('cart');
        if (($item_index = $this->getItemIndex($id)) == -1) {
            return null;
        }
        return $cart[$item_index]['special_instructions'];
    }
}
