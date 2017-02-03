<?php

namespace App\CustomTraits;

use Session;

trait CartInformation
{

    protected $max_items_in_cart;

    public function __construct()
    {
        $this->max_items_in_cart = 6;
    }

    public function cart_has_valid_number_of_items($id = -1)
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

    public function item_exists_in_cart($id)
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

    private function max_item_in_cart_error($name_of_item)
    {
        return $name_of_item .
            " could not be added to cart. 
            You can have a max of " . $this->max_items_in_cart . " items in the cart";
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

}