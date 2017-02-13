<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
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
}
