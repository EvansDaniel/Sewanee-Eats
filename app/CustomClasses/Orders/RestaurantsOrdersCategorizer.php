<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/26/17
 * Time: 2:37 PM
 */

namespace App\CustomClasses\Orders;


use App\Models\Order;
use App\Models\Restaurant;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Categorizes orders by there restaurants and maps them
 * to the orders items
 * Class RestaurantsOrdersCategorizer
 * @package App\CustomClasses\Orders
 */
class RestaurantsOrdersCategorizer
{
    protected $orders;
    protected $rest_to_orders;
    protected $rests_orders_mapping;

    public function __construct($orders)
    {
        if (count($orders) == 0)
            throw new InvalidArgumentException("orders cannot be empty and must be a collection");
        $this->orders = $orders;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function getRestaurantOrdersMapping()
    {
        if (!empty($this->rests_orders_mapping)) {
            return $this->rests_orders_mapping;
        }
        if (empty($this->rest_to_orders)) {
            $this->mapRestToOrders();
        }
        $rests_to_orders_mapping = [];
        foreach ($this->rest_to_orders as $r_id => $orders) {
            $rest = Restaurant::find($r_id);
            $rests_to_orders_mapping[] = new RestaurantOrdersMapping($rest, $orders);
        }
        return $this->rests_orders_mapping = $rests_to_orders_mapping;
    }

    /**
     * IMPORTANT: THIS FUNCTION ASSUMES THAT THERE IS ONLY ONE
     * RESTAURANT PER ORDER
     * Builds the mapping of all the restaurants for a set of orders
     * to the orders that have that restaurant
     * Mapping: $restaurant => [ $orders (that have $restaurant) ]
     */
    public function mapRestToOrders()
    {
        if (!empty($this->rest_to_orders)) {
            return $this->rest_to_orders;
        }
        $rests_to_order = [];
        foreach ($this->orders as $order) {
            $order_rest_ids = $this->getOrderRestaurantIds($order);
            foreach ($order_rest_ids as $order_rest_id) {
                $rests_to_order[(string)$order_rest_id][] = $order;
            }
        }
        $this->rest_to_orders = $rests_to_order;
        return $this->rest_to_orders;
    }

    private function getOrderRestaurantIds(Order $order): array
    {
        $rests = $order->getRestaurants();
        $rest_ids = [];
        foreach ($rests as $rest) {
            $rest_ids[] = $rest->id;
        }
        return $rest_ids;
    }

}