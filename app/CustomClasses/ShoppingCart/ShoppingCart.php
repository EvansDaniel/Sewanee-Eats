<?php

namespace App\CustomClasses\ShoppingCart;

use App\CustomTraits\PriceInformation;
use Session;

/**
 * Class ShoppingCart abstraction between the controller logic and the cart business logic as well as the cart data that lives in the session
 * @package App\CustomClasses
 */
class ShoppingCart
{
    use PriceInformation;

    protected $max_items_in_cart;
    protected $max_on_demand_items;
    protected $num_on_demand_items;
    protected $next_cart_item_id;
    protected $cart;

    public function __construct()
    {
        $this->max_items_in_cart = 10;
        $this->max_on_demand_items = 3;
        $this->next_cart_item_id = Session::get('next_cart_item_id');
        $this->cart = Session::get('cart');
    }

    /**
     * @return int
     */
    public function getMaxItemsInCart()
    {
        return $this->max_items_in_cart;
    }

    /**
     * @return int
     */
    public function getMaxOnDemandItems()
    {
        return $this->max_on_demand_items;
    }

    /**
     * @return mixed
     */
    public function getNumOnDemandItems()
    {
        return $this->num_on_demand_items;
    }

    public function quantity()
    {
        return count($this->cart);
    }

    public function getOnDemandItems()
    {

    }

    public function getWeeklySpecialItems()
    {

    }

    public function getEventItems()
    {

    }

    public function categorizedItems()
    {
        $items = [];
        foreach ($this->cart as $cart_item) {
            if ($cart_item->getSellerEntity()->getSellerType() == SellerType::ON_DEMAND) {
                $items["on_demand"][] = $cart_item;
            } else if ($cart_item->getSellerEntity()->getSellerType() == SellerType::WEEKLY_SPECIAL) {
                $items["weekly_special"][] = $cart_item;
            } else if ($cart_item->getSellerEntity()->getSellerType() == SellerType::EVENT) {
                $items["event"][] = $cart_item;
            }
        }
        return $items;
    }

    /**
     * Adds CartItem item(s) to the cart
     * @param $cart_items array CartItem
     */
    public function putItems($cart_items)
    {
        foreach ($cart_items as $cart_item) {
            $cart_item->setCartItemId($this->nextCartId());
            $this->cart[] = $cart_item;
        }
        Session::put('cart', $this->cart);
    }

    private function nextCartId()
    {
        if (empty($this->next_cart_item_id)) {
            $this->next_cart_item_id = 1;
        }
        $temp_id = $this->next_cart_item_id;
        $this->next_cart_item_id++;
        // persist the next_cart_item_id so that the next time a cart item is added
        // it will get the correct id
        Session::put('next_cart_item_id', $this->next_cart_item_id);
        return $temp_id;
    }

    /**
     * @param $cart_item_id integer the unique cart id given by the shopping cart
     * @param $si string special instructions to update with
     */
    public function setInstructions($cart_item_id, $si)
    {
        foreach ($this->cart as $cart_item) {
            if ($cart_item->getCartItemId($cart_item_id)) {
                $cart_item->setInstructions($si);
            }
        }
    }

    /**
     * worker for the JS endpoint
     * @param $cart_item_id integer the unique cart id given by the shopping cart
     * @param $extras array of Accessory ids that are the ids of the accessories to add
     */
    public function setExtras($cart_item_id, $extras)
    {
        foreach ($this->cart as $cart_item) {
            if ($cart_item->getCartItemId($cart_item_id)) {
                $cart_item->setExtras($extras);
            }
        }
    }

    public function removeItem($cart_item_id)
    {
        $size = count($this->cart);
        for ($i = 0; $i < $size; $i++) {
            if ($this->cart[$i]->getCartItemId() == $cart_item_id) {
                unset($this->cart[$i]);
                array_values($this->cart);
                break;
            }
        }
    }

    public function hasOnDemandOverflow($cart_items)
    {
        if (empty($cart_items)) {
            return false;
        }
        foreach ($cart_items as $cart_item) {
            $num_on_demand_items = 0;
            // check each item in the cart, if it is an on demand item, increment on demand orders
            if ($cart_item->getSellerEntity()->getSellerType() == SellerType::ON_DEMAND) {
                $num_on_demand_items++;
                if (($num_on_demand_items + $this->num_on_demand_items) == $this->max_on_demand_items)
                    return true;
            }
        }
        return false;
    }

    public function extras($cart_item_id)
    {
        $cart_item = $this->getItem($cart_item_id);
        if (!empty($cart_item)) {
            return $cart_item->extras();
        }
        return null;
    }

    public function getItem($cart_item_id)
    {
        foreach ($this->cart as $item) {
            if ($item->getCartItemId() == $cart_item_id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * @return array of CartItem, which are the items in the shopping cart
     */
    public function items()
    {
        return Session::get('cart');
    }

}