<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/26/17
 * Time: 2:55 PM
 */

namespace App\CustomClasses\Orders;


use App\Models\Restaurant;

/**
 * Stores the mapping between a Restaurant and a set of orders
 * that share that same restaurant
 * Class RestaurantOrdersMapping
 * @package App\CustomClasses\Orders
 */
class RestaurantOrdersMapping
{
    private $rest;
    private $orders;

    public function __construct(Restaurant $rest, $orders)
    {

        $this->rest = $rest;
        $this->orders = $orders;
    }

    public function getRest(): Restaurant
    {
        return $this->rest;
    }

    public function getOrders()
    {
        return $this->orders;
    }


}