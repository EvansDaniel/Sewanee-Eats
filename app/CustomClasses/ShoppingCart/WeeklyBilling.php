<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:57 PM
 */

namespace App\CustomClasses\ShoppingCart;


use App\Models\Accessory;

class WeeklyBilling
{
    protected $cart;
    protected $delivery_fee;
    protected $discount;
    protected $number_of_items;
    protected $items_with_discount;
    protected $markup;
    /**
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->delivery_fee = 3;
        $this->discount = .6;
        $this->number_of_items = $this->countWeeklyItems();
        $this->items_with_discount = $this->countItemsWithDiscount();
        $this->markup = 0.75;
    }

    /**
     * @return ShoppingCart|null
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return int
     */
    public function getDeliveryFee()
    {
        return $this->delivery_fee;
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return array
     */
    public function getNumberOfItems()
    {
        return $this->number_of_items;
    }

    /**
     * @return array|int
     */
    public function getItemsWithDiscount()
    {
        return $this->items_with_discount;
    }



    public function countItemsWithDiscount()
    {
        if($this->number_of_items < 5 && $this->number_of_items >1){
            return $this->number_of_items -1;
        }
        else if($this->number_of_items >= 5){
            return 3;
        }
        else{
            return 0;
        }
    }

    public function countWeeklyItems()
    {
        return($this->cart->getWeeklySpecialItems());
    }
    /**
     * @return float
     */
    public function getMarkup()
    {
        return $this->markup;
    }
}