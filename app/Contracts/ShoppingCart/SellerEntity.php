<?php

/**
 * Interface SellerEntity operations on the SellerEntity for a Item
 */
namespace App\Contracts\ShoppingCart;

interface SellerEntity
{
    public function getName();

    public function getDesc();

    public function getLocation();

    public function getSellerType();

    public function isForProfit();
    /*public function getAvailability();*/
}