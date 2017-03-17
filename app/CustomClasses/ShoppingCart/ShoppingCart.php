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

    /**
     * @var int the max number of items allowed in the cart
     */
    protected $max_items_in_cart;
    /**
     * @var int the max number of On Demand items allowed in the cart
     */
    protected $max_on_demand_items;
    /**
     * @var int stores the current number of On Demand items in the cart
     */
    protected $num_on_demand_items;
    /**
     * @var mixed stores the next unique cart id
     * On the first instantiation of the class, this variable will remain
     * null, as the Session variable "next_cart_item_id" will not be set.
     * Subsequent instantiations will have this variable set to the aforementioned
     * Session variable
     */
    protected $next_cart_item_id;
    /**
     * @var array the set of CartItem(s) of On Demand items in the cart
     */
    protected $on_demand_items;
    /**
     * @var array the set of CartItem(s) of Weekly Special items in the cart
     */
    protected $weekly_special_items;
    /**
     * @var array the set of CartItem(s) of Event items in the cart
     */
    protected $event_items;
    /**
     * @var array the set of CartItem(s) which are stored in the session
     * variable 'cart'
     */
    protected $cart;
    /**
     * @var integer number of items in the cart, == to count($this->cart)
     */
    protected $quantity;

    public function __construct()
    {
        $this->max_items_in_cart = 10;
        $this->max_on_demand_items = 3;
        $this->next_cart_item_id = Session::get('next_cart_item_id');
        $this->cart = Session::get('cart');
        $this->categorizedItems();
        $this->quantity = $this->quantity();
        $this->num_on_demand_items = $this->countOnDemandItems();
    }

    /**
     * Categorizes the items in the cart to three different
     * categories:
     * On Demand, Weekly Special, and Event
     * It will then set the corresponding instance variables
     * possibly to null, so make sure to check for it
     */
    private function categorizedItems()
    {
        $items = [
            'on_demand' => null,
            'weekly_special' => null,
            'event' => null
        ];
        if (!empty($this->cart)) {
            foreach ($this->cart as $cart_item) {
                if ($cart_item->getSellerEntity()->getSellerType() == SellerType::ON_DEMAND) {
                    $items["on_demand"][] = $cart_item;
                } else if ($cart_item->getSellerEntity()->getSellerType() == SellerType::WEEKLY_SPECIAL) {
                    $items["weekly_special"][] = $cart_item;
                } else if ($cart_item->getSellerEntity()->getSellerType() == SellerType::EVENT) {
                    $items["event"][] = $cart_item;
                }
            }
        }
        $this->event_items = $items['on_demand'];
        $this->weekly_special_items = $items["weekly_special"];
        $this->event_items = $items['event'];
    }

    private function quantity()
    {
        return count($this->cart);
    }

    public function countOnDemandItems()
    {
        return count($this->on_demand_items);
    }

    /**
     * @return int
     */
    public function getMaxItemsInCart()
    {
        return $this->max_items_in_cart;
    }

    public function getQuantity()
    {
        return $this->quantity;
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

    public function getOnDemandItems()
    {
        return $this->on_demand_items;
    }

    public function getWeeklySpecialItems()
    {
        return $this->weekly_special_items;
    }

    public function getEventItems()
    {
        return $this->event_items;
    }

    /**
     * @return bool true if the the cart contains
     * the max number of on demand items i.e. $this->max_on_demand_items
     *
     * Note: this function depends on the correctness of
     * the categorizedItems() function
     * @todo could store this in the session possibly to make it faster?
     */
    public function hasMaxOnDemandItems()
    {
        return $this->num_on_demand_items == $this->max_on_demand_items;
    }

    /**
     * @return bool true if the cart contains max items in the cart
     * $this->max_items_in_cart
     */
    public function hasMaxItems()
    {
        return $this->quantity() == $this->max_items_in_cart;
    }

    /**
     * Adds CartItem item(s) to the cart
     * @param $cart_items array CartItem to add
     * Saves $cart_items into the Session, setting up
     * a unique cart item id for each item added
     */
    public function putItems($cart_items)
    {
        foreach ($cart_items as $cart_item) {
            $cart_item->setCartItemId($this->nextCartId());
            $this->cart[] = $cart_item;
        }
        $this->save();
    }

    /**
     * Helper function to return the next unique cart item id
     * It will start the count of ids at 1 and increment it once
     * after each call to the function. The function will save
     * the next cart_item_id to the session so that the next
     * time the cart is instantiated, the next unique cart id will
     * be used and stored in $this->next_cart_item_id
     * @return int|mixed
     */
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

    public function save()
    {
        Session::put('cart', $this->cart);
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

    /**
     * @param $cart_items
     * @return bool
     */
    public function hasOnDemandOverflow($cart_items)
    {
        if (empty($this->cart)) {
            return false;
        }
        $num_on_demand_items = 0;
        foreach ($cart_items as $cart_item) {
            // check each item in the cart, if it is an on demand item, increment on demand orders
            if ($cart_item->getSellerEntity()->getSellerType() == SellerType::ON_DEMAND) {
                $num_on_demand_items++;
                if ($num_on_demand_items == $this->max_on_demand_items)
                    return true;
            }
        }
        return false;
    }

    /**
     * @param $cart_item_id integer the id of the cart item
     * whose extras you are trying to retrieve
     * @return array of Accessory ids
     */
    public function extras($cart_item_id)
    {
        $cart_item = $this->getItem($cart_item_id);
        if (!empty($cart_item)) {
            return $cart_item->extras();
        }
        return null;
    }

    /**
     * @param $cart_item_id integer the cart item id of the item to find
     * and return
     * @return CartItem the item in the cart found
     *
     * Searches the cart to find the an item with $cart_item_id
     */
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
     * @param $cart_item_id integer the id of the item in the cart
     * @param $si string the instructions to change the instructions
     * associated with the item given by $cart_item_id to
     *
     * Searches the cart for an item with id == $cart_item_id and
     * if found, updates that items instructions to $si and returns
     * if item with id == $cart_item_id is not found, nothing happens
     */
    public function updateInstructions($cart_item_id, $si)
    {
        for ($i = 0; $i < $this->quantity(); $i++) {
            if ($this->cart[$i]->getCartItemId() == $cart_item_id) {
                $this->cart[$i]->setInstructions($si);
                $this->save();
                break;
            }
        }
    }

    /**
     * @param $cart_item_id integer the id of the item in the cart
     * whose extra you wish to toggle
     * @param $extra_id integer the id of the Accessory whose is
     * an accessory of the underlying item
     *
     * This function will switch the extra "on" if the user currently
     * doesn't have that accessory chosen. If the user has previously
     * chosen that Accessory, the accessory will be switched "off"
     * If the given $cart_item_id doesn't exist, nothing will happen
     * If the given $extra_id doesn't exist, nothing will happen
     */
    public function toggleExtras($cart_item_id, $extra_id)
    {
        for ($i = 0; $i < $this->quantity(); $i++) {
            if ($this->cart[$i]->getCartItemId() == $cart_item_id) {
                $extra_exists = false;
                for ($j = 0; $j < count($this->cart[$i]->getExtras()); $j++) {
                    $extras = $this->cart[$i]->getExtras();
                    if ($extra_id == $extras[$j]) {
                        $extra_exists = true;
                        unset($extras[$j]);
                        $this->cart[$i]->setExtras(array_values($extras));
                    }
                }
                if (!$extra_exists) {
                    $extras = $this->cart[$i]->getExtras();
                    $extras[] = $extra_id;
                    $this->cart[$i]->setExtras(array_values($extras));
                }
                $this->save();
                break;
            }
        }
    }

    /**
     * @param $cart_item_id integer the id of the item
     * in the cart to delete permanently
     */
    public function deleteItem($cart_item_id)
    {
        for ($i = 0; $i < $this->quantity(); $i++) {
            if ($this->cart[$i]->getCartItemId() == $cart_item_id) {
                unset($this->cart[$i]);
                $this->cart = array_values($this->cart);
                $this->save();
                break;
            }
        }
    }

    /**
     * @return array of CartItem, which are the items in the shopping cart
     */
    public function items()
    {
        return Session::get('cart');
    }

    /**
     * Debugging function
     */
    private function printCart()
    {
        foreach ($this->cart as $index => $cart_item) {
            \Log::info($index . " => " . $cart_item->getName());
        }
    }

}