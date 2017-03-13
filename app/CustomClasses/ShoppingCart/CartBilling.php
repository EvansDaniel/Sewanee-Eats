<?php

namespace App\CustomClasses\ShoppingCart;


class CartBilling
{
    protected $cart;
    protected $delivery_fee;
    protected $discount;
    protected $subtotal;
    protected $total;
    protected $tax;
    protected $tax_percent;
    protected $base_delivery_fee;
    protected $mark_up_per_item;

    /**
     * CartBilling constructor.
     * @param ShoppingCart|null $cart For displaying business info, like prices, no need to pass a shopping cart
     * else pass the shopping cart to compute billing information
     */
    public function __construct(ShoppingCart $cart = null)
    {
        $this->cart = $cart;
        $this->tax_percent = 1.0925;
        $this->base_delivery_fee = 3;
        $this->mark_up_per_item = .75;
        $this->delivery_fee = $this->deliveryFee();
        $this->discount = $this->discount();
        $this->subtotal = $this->subtotal();
        $this->total = $this - $this->subtotal + $this->tax;
    }

    private function deliveryFee()
    {
        return 0;
    }

    private function discount()
    {
        return 0;
    }

    private function subtotal()
    {
        return 0;
    }

    /**
     * @return float
     */
    public function getTaxPercent()
    {
        return $this->tax_percent;
    }

    /**
     * @return int
     */
    public function getBaseDeliveryFee()
    {
        return $this->base_delivery_fee;
    }

    /**
     * @return float
     */
    public function getMarkUpPerItem()
    {
        return $this->mark_up_per_item;
    }

    /**
     * @return mixed
     */
    public function getDeliveryFee()
    {
        return $this->delivery_fee;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    private function tax()
    {
        return 0;
    }

    private function totalPrice()
    {
        return 0;
    }


}