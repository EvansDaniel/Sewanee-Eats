<?php

/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 3/12/17
 * Time: 5:24 PM
 */
namespace App\Contracts\ShoppingCart;

interface Item
{
    public function getPrice();

    public function getDesc();

    public function getSellerEntity();

    public function extras();
}