<?php

namespace App\Models;

use App\Contracts\ShoppingCart\SellerEntity;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model implements SellerEntity
{

    protected $table = "restaurants";

    public function shiftExists($day, $shift)
    {
        if (!$this->available_times)
            return false;
        if (!$this->available_times[$day][$shift])
            return false;
        return true;
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'restaurants_orders',
            'restaurant_id', 'order_id');
    }

    // has many menu items
    public function menuItems()
    {
        return $this->hasMany('App\Models\MenuItem',
            'restaurant_id', 'id');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDesc()
    {
        return "";
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getSellerType()
    {
        return $this->seller_type;
    }
}
