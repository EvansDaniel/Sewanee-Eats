<?php

namespace App\CustomTraits;

use Session;

trait CartInformation
{

    protected $max_items_in_cart;

    public function __construct()
    {
        $this->max_items_in_cart = 10;
    }

    public function cartHasValidNumberOfItems($id = -1)
    {
        return $this->getCartQuantity($id) < $this->max_items_in_cart;
    }

    /**
     * id of item is unsigned, so -1 works as a default value
     * @param int $id the id of the item to exclude in the summation
     * @return int returns the total quantity of items in the cart
     */
    public function getCartQuantity($id = -1)
    {
        $cart = Session::get('cart');
        if (empty($cart))
            return 0;
        $number_of_items = 0;
        foreach ($cart as $item) {
            if ($id == $item['menu_item_model']->id)
                continue;
            $number_of_items += $item['quantity'];
        }
        return $number_of_items;
    }

    public function cartHasItem($id)
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

    public
    function itemIsFromDifferentRestaurant($r_id)
    {
        $cart = Session::get('cart');
        if (empty($cart)) return true;
        foreach ($cart as $item) {
            if ($item['menu_item_model']->restaurant->id == $r_id) {
                return false;
            }
        }
        return true;
    }

    public
    function addCartItemQuantity($id, $quantity, $item_index = -2)
    {
        $cart = Session::get('cart');
        if ($item_index < 0) {
            if (($item_index = $this->getItemIndex($id)) == -1) {
                return null;
            }
        }
        $cart[$item_index]['quantity'] += $quantity;
        Session::put('cart', $cart);
    }

    /**
     * @param $id integer the id of a menu item
     * @return integer returns -1 if the item does not exist
     *                 Otherwise, it returns the index of the object into the
     *                 cart session array
     */
    private function getItemIndex($id)
    {
        $cart = Session::get('cart');
        if (empty($cart)) {
            return -1;
        }
        // using unset means that the index of the cart array
        // might have gaps i.e. it could look like this [ 0 => item0,
        // 1 => item1, 3 => item3. So use foreach loop which uses the
        // exact key from the array as is
        foreach ($cart as $index => $item) {
            if ($id == $cart[$index]['menu_item_model']->id) {
                return $index;
            }
        }
        return -1;
    }

    public
    function addItemExtras($id, $extras, $item_index = -2)
    {
        $cart = Session::get('cart');
        if ($item_index < 0) { // not checking for out of bounds on high end, just don't mess up
            if (($item_index = $this->getItemIndex($id)) == -1) {
                return null;
            }
        }
        if (!empty($cart[$item_index]['extras'])) {
            $cart[$item_index]['extras'] = $this->cartArrayPush($cart[$item_index]['extras'], $extras);
        }
        Session::put('cart', $cart);
    }

    public function cartArrayPush($arrayToAppendTo, $arrayToAppend)
    {
        if (empty($arrayToAppendTo))
            return $arrayToAppend;
        if (empty($arrayToAppend)) {
            return $arrayToAppendTo;
        }
        foreach ($arrayToAppend as $item) {
            $arrayToAppendTo[] = $item;
        }
        return $arrayToAppendTo;
    }

    private function maxItemInCartError($name_of_item)
    {
        return $name_of_item .
            " could not be added to cart. 
            You can have a max of " . $this->max_items_in_cart . " items in the cart";
    }

}