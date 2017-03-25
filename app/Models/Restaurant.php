<?php

namespace App\Models;

use App\Contracts\Availability;
use App\Contracts\ShoppingCart\SellerEntity;
use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model implements SellerEntity, Availability
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
        return $this->address;
    }

    public function scopeWeeklySpecials($query)
    {
        return $query->where('seller_type', RestaurantOrderCategory::WEEKLY_SPECIAL);
    }

    public function scopeOnDemand($query)
    {
        return $query->where('seller_type', RestaurantOrderCategory::ON_DEMAND);
    }

    public function timeRanges()
    {
        return $this->hasMany('App\Models\TimeRange', 'restaurant_id', 'id');
    }

    /**
     * @return array|TimeRange
     */
    public function getAvailability()
    {
        return $this->timeRanges;
    }

    public function isForProfit()
    {
        return true;
    }

    public function getSellerType()
    {
        return $this->seller_type;
    }

    public function isOpen()
    {
        $is_avail = new IsAvailable($this);
        return $is_avail->isAvailableNow();
    }


}
