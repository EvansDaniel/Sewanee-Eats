<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/12/17
 * Time: 6:22 PM
 */

namespace App\CustomClasses\ShoppingCart;

/**
 * Class Order Handles and saves the order, regardless of the type of order
 * @package App\CustomClasses
 */
class Order
{
    protected $billing;
    protected $cart;

    public function __construct(ShoppingCart $cart, CartBilling $billing)
    {
        $this->cart = $cart;
        $this->billing = $billing;
    }

    public function handleVenmoOrder()
    {

    }

    public function handleStripeOrder()
    {

    }
}