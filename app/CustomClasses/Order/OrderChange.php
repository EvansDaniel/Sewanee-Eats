<?php

namespace App\CustomClasses\ShoppingCart;

use App\Models\Order;

/**
 * Class OrderChange Handles any changes to an order i.e. updates, cancellations, refunds, etc
 * @package App\CustomClasses\ShoppingCart
 */
class OrderChange
{
    protected $order;

    public function __construct(Order $order)
    {

    }

    // TODO: look into stripe API for refunds
    public function refundItem()
    {

    }

    public function refundOrder()
    {

    }

    public function cancelOrder()
    {

    }

    public function updateLocation()
    {

    }

    /**
     * Closes the order
     */
    public function closeOrder()
    {

    }


}