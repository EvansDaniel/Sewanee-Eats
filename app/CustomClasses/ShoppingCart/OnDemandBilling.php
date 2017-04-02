<?php
/**
 * Created by PhpStorm.
 * User: blaise
 * Date: 4/1/17
 * Time: 3:57 PM
 */

namespace App\CustomClasses\ShoppingCart;


use App\Models\Accessory;
use App\Models\MenuItem;

class OnDemandBilling
{
    protected $cart;
    protected $delivery_fee;
    protected $extraFee;
    protected $number_of_items;
    protected $min_items_without_fee;
    protected $on_demand_items;
    protected $on_demand_cost;
    protected $fee_after;
    protected $on_demand_profit;

    /**
     * @return int
     */
    public function getOnDemandProfit()
    {
        return $this->on_demand_profit;
    }
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
        $this->extraFee = 0.30;
        $this->number_without_fee_items = 2;
        $this->on_demand_items = $cart->getOnDemandItems();
        $this->on_demand_cost = $this->costOfOnDemand();
        $this->fee_after = $this->fee();
        $this->on_demand_profit = $this->demandProfit();
    }

    public function fee()
    {
        if($this->number_of_items >2){
            return $this->delivery_fee + (($this->number_of_items - 2)* $this->extraFee);
        }
        else if($this->number_of_items > 0 && $this->number_of_items <=2){
            return $this->delivery_fee ;
        }
        else
            return 0;
    }
    /**
     * @return int
     */
    public function getOnDemandCost()
    {
        return $this->on_demand_cost;
    }

    /**
     * @return array
     */
    public function getOnDemandItems()
    {
        return $this->on_demand_items;
    }

    public function costOfOnDemand()
    {
        $cost = 0;
        if (!empty($this->on_demand_items)){
            foreach ($this->on_demand_items as $item){
                $cost += $item->getPrice();
            }
        }
        return $cost;
    }

    /**
     * @return int
     */
    public function getFeeAfter()
    {
        return $this->fee_after;
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

    public function demandProfit()
    {
        return $this->getDeliveryFee();
    }



}