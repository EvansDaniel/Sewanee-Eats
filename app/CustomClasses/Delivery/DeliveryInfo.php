<?php

namespace App\CustomClasses\Delivery;

use App\Contracts\HasItems;
use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\Helpers\HttpRequest;
use App\CustomClasses\Helpers\UrlBuilder;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;

class DeliveryInfo
{
    protected $GOOGLE_DISTANCE_MATRIX_API_URL;
    protected $container;
    protected $starting_loc;
    protected $total_distance;
    protected $mtrs_per_mile;
    protected $dist_biker_can_travel;
    protected $dist_walker_can_travel;
    protected $on_campus_delivery_threshold;

    /**
     * DeliveryInfo constructor.
     * @param HasItems $hasItems expected to be an object that implements the items method
     * and each of these 'items' is a CartItem
     */
    public function __construct(HasItems $hasItems)
    {
        $this->container = $hasItems;
        // https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Washington,DC&destinations=New+York+City,NY&key=YOUR_API_KEY
        $this->GOOGLE_DISTANCE_MATRIX_API_URL =
            'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial';
        $this->starting_loc = '735 University Ave, Sewanee, TN 37383';
        $this->API_KEY = env('GOOGLE_MAPS_KEY');
        $this->mtrs_per_mile = 1609.34;
        $this->on_campus_delivery_threshold = 4;
        $this->dist_biker_can_travel = $this->on_campus_delivery_threshold; // miles
        $this->dist_walker_can_travel = $this->on_campus_delivery_threshold; // miles
    }

    public static function getMaxRestaurantCourierPayment(Order $order)
    {
        $max = -999999;
        foreach ($order->menuItemOrders as $menu_item_order) {
            $courier_payment = $menu_item_order->item->restaurant->courier_payment;
            if ($courier_payment > $max) {
                $max = $courier_payment;
            }
        }
        return $max;
    }

    /**
     * @return array the CourierType(s) that can deliver this order
     */
    public function getCourierTypesForItems()
    {
        $courier_types = [];
        $rest_distance = $this->sumRestDistances();
        if ($rest_distance < $this->dist_biker_can_travel) {
            $courier_types[] = CourierTypes::BIKER;
            if ($rest_distance < $this->dist_walker_can_travel) {
                $courier_types[] = CourierTypes::WALKER;
            }
        }
        // driver cannot take the order for inside $this->dist_biker_can_travel (unless all bikers/walkers are busy?)
        if (empty($courier_types)) {
            $courier_types[] = CourierTypes::DRIVER;
        }
        return $courier_types;
    }

    private function sumRestDistances()
    {
        // each item must be a cart item
        $starting_loc = $this->starting_loc;
        $dest_loc = null;
        $total_distance = null;
        foreach ($this->container->items() as $item) {
            // if this is an on demand restaurant
            if ($item->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
                $dest_loc = $item->getSellerEntity()->getLocation();
                $dist = $this->makeDistanceRequest($starting_loc, $dest_loc);
                $total_distance += $dist;
                $starting_loc = $dest_loc;
            }
        }
        if (!empty($dest_loc)) { // there might be no items (thus no destinations)
            $dist = $this->makeDistanceRequest($starting_loc, $dest_loc);
            $total_distance += $dist;
        }
        return round($total_distance);
    }

    private function makeDistanceRequest($starting_loc, $dest_loc)
    {
        $builder = new UrlBuilder($this->GOOGLE_DISTANCE_MATRIX_API_URL);
        $data = [
            'origins' => $starting_loc,
            'destinations' => $dest_loc,
            'key' => $this->API_KEY
        ];
        $builder->addParams($data);
        $http = new HttpRequest($builder);
        $res = json_decode($http->get(), true);
        return $this->getDistance($res);
    }


    /**
     * @param $res array the result from the google maps distance matrix api call
     * @return integer the distance between the origin and destination
     * passed to the distance matrix api call
     */
    private function getDistance($res)
    {
        return $res['rows'][0]['elements'][0]['distance']['value'] / $this->mtrs_per_mile;
    }
}