<?php

namespace App\CustomClasses\ShoppingCart;

use App\Models\Accessory;

class SpecialBilling
{
    protected $cart;
    protected $base_delivery_fee;
    protected $delivery_fee;
    protected $discount;
    protected $markup;
    protected $cost_of_food;
    protected $special_profit;
    protected $discount_value;
    protected $num_weekly_special_items;

    /**
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->base_delivery_fee = 3;
        $this->discount_value = .6;
        $this->num_weekly_special_items = count($this->cart->getSpecialItems());
        $this->markup = 0.75;
        $this->cost_of_food = $this->costOfFood();
        $this->delivery_fee = $this->deliveryFee();
        $this->special_profit = $this->specialProfit();
        $this->discount = $this->countItemsWithDiscount() * $this->discount_value;

    }

    public function costOfFood()
    {
        $cost = 0;
        if (!empty($this->cart->getSpecialItems())) {
            foreach ($this->cart->getSpecialItems() as $item) {
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

    public function deliveryFee()
    {
        if (empty($this->cart->getSpecialItems())) {
            return 0;
        } else {
            return $this->getBaseDeliveryFee() - ($this->countItemsWithDiscount() * $this->getDiscountValue());
        }
    }

    public function getBaseDeliveryFee()
    {
        return $this->base_delivery_fee;
    }

    public function countItemsWithDiscount()
    {
        return $this->num_weekly_special_items > 1 ?
            min($this->num_weekly_special_items - 1, 3) : 0;
    }

    /**
     * @return float
     */
    public function getDiscountValue()
    {
        return $this->discount_value;
    }

    public function specialProfit()
    {
        if ($this->getNumberOfWeeklySpecialItems() == 0) {
            return 0;
        } else {
            return $this->getBaseDeliveryFee() - ($this->countItemsWithDiscount() * $this->getDiscountValue());
        }
    }

    /**
     * @return array
     */
    public function getNumberOfWeeklySpecialItems()
    {
        return $this->num_weekly_special_items;
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
    public function getCostOfFood()
    {
        return $this->cost_of_food;
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return float
     */
    public function getMarkup()
    {
        return $this->markup;
    }

    /**
     * @return int
     */
    public function getSpecialProfit()
    {
        return $this->special_profit;
    }

    public function discount()
    {
        return $this->countItemsWithDiscount() * $this->discount_value;
    }
}