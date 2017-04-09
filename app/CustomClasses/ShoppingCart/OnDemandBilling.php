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
    protected $extra_fee_per_item;
    protected $num_on_demand_items;
    protected $max_num_items_without_extra_fee;
    protected $on_demand_cost;
    protected $extra_fee;
    protected $on_demand_profit;
    protected $courier_payment;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->delivery_fee = $this->deliveryFee();
        $this->num_on_demand_items = count($cart->getOnDemandItems());
        $this->extra_fee_per_item = 0.30;
        $this->max_num_items_without_extra_fee = 2;
        $this->cost_of_food = $this->costOfFood();
        $this->extra_fee = $this->extraFee();
    }

    public function deliveryFee()
    {
        $max = 0;
        if (!empty($this->cart->getOnDemandItems())) {
            foreach ($this->cart->getOnDemandItems() as $item) {
                $courier_payment = $item->getSellerEntity()->delivery_payment_for_courier;
                if ($courier_payment > $max) {
                    $max = $courier_payment;
                }
            }
        }
        $this->courier_payment = $max;
        // if there are no on demand items in the cart, then the delivery fee is 0, otherwise the max + 1
        return $max == 0 ? 0 : $max + 1;
    }

    public function costOfFood()
    {
        $cost = 0;
        if (!empty($this->cart->getOnDemandItems())) {
            foreach ($this->cart->getOnDemandItems() as $item) {
                $cost += $item->getPrice();
                if (!empty($item->getExtras())) {
                    foreach ($item->getExtras() as $acc) {
                        $cost += Accessory::find($acc)->price;
                    }
                }
            }
        }
        return $cost;
    }

    public function extraFee()
    {
        if ($this->num_on_demand_items > $this->max_num_items_without_extra_fee) {
            return (($this->num_on_demand_items - $this->max_num_items_without_extra_fee) * $this->getExtraFeeCostPerItem());
        }
        return 0;
    }

    public function getExtraFeeCostPerItem()
    {
        return $this->extra_fee_per_item;
    }

    /**
     * The discount for on demand items as a percentage
     * @return int
     */
    public function getDiscount()
    {
        return 0;
    }

    public function getMaxItemsBeforeExtraFee()
    {
        return $this->max_num_items_without_extra_fee;
    }

    public function getOnDemandProfit()
    {
        return $this->getDeliveryFee() - $this->getCourierPayment();
    }

    /**
     * @return int
     */
    public function getDeliveryFee()
    {
        return $this->delivery_fee + $this->extra_fee;
    }

    public function getCourierPayment()
    {
        return $this->courier_payment;
    }

    /**
     * @return int
     */
    public function getCostOfFood()
    {
        return $this->cost_of_food;
    }

    /**
     * @return int
     */
    public function getFeeAfter()
    {
        return $this->extra_fee;
    }

    /**
     * @return float
     */
    public function getExtraFee()
    {
        return $this->extra_fee;
    }

}