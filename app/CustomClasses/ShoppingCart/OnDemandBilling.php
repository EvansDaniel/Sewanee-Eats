<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:57 PM
 */

namespace App\CustomClasses\ShoppingCart;


use App\Models\Accessory;

class OnDemandBilling
{
    protected $cart;
    protected $delivery_fee;
    protected $extraFee;
    protected $number_of_items;
    protected $min_items_without_fee;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->delivery_fee = 4;
        $this->number_of_items = $cart->countOnDemandItems();
        $this->extraFee = 0.40;
        $this->number_without_fee_items = 2;
    }

    /**
     * @return int
     */
    public function getNumberWithoutFeeItems()
    {
        return $this->number_without_fee_items;
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
     * @return float
     */
    public function getExtraFee()
    {
        return $this->extraFee;
    }

    /**
     * @return int
     */
    public function getNumberOfItems()
    {
        return $this->number_of_items;
    }

    /**
     * @return mixed
     */
    public function getMinItemsWithoutFee()
    {
        return $this->min_items_without_fee;
    }




}