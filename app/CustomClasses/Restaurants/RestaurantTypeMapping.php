<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 4/22/17
 * Time: 1:53 AM
 */

namespace App\CustomClasses\Restaurants;


class RestaurantTypeMapping
{
    protected $rest_type; // the acutal restaurant type i.e. RestaurantOrderCategory::$VAR$
    protected $description; // The description of the restaurant type

    public function __construct($rest_type, $description)
    {
        $this->rest_type = $rest_type;
        $this->description = $description;
    }

    public function getRestType()
    {
        return $this->rest_type;
    }

    public function getDescription()
    {
        return $this->description;
    }
}