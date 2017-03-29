<?php

namespace App\Models;

use App\Contracts\Availability;
use App\Contracts\ResourceTimeRange;
use App\Contracts\ShoppingCart\SellerEntity;
use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomTraits\HandlesTimeRanges;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model implements SellerEntity, Availability, ResourceTimeRange
{

    use HandlesTimeRanges;
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

    /**
     * This determines if we are allowing users to see and buy from
     * this restaurant at this time. It is unrelated to a restaurants
     * time ranges i.e. its open times for on demand and its payment time frame
     * for weekly specials
     */
    public function isAvailableToCustomers()
    {
        return $this->is_available_to_customers;
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
        if ($this->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL)) {
            // TODO: make this only get the first element in array
            // get first element cause there should only
            // be one payment time frame for a weekly special
            $this_time_ranges = $this->timeRanges;
            if (count($this_time_ranges) >= 1) {
                // adding time_ranges for weekly special not required on rest creation
                return $this->timeRanges[0];
            } else {
                return null;
            }
        } else {
            return $this->timeRanges;
        }
    }

    public function isSellerType($type)
    {
        return $this->getSellerType() == $type;
    }

    public function getSellerType()
    {
        return $this->seller_type;
    }

    public function isForProfit()
    {
        return true;
    }

    public function getResourceTimeRangesByDay($dow)
    {
        $time_range_type = $this->getTimeRangeType();
        return $this->getTimeRangesByDay($dow, $time_range_type, 'restaurant_id');
    }

    public function getTimeRangeType()
    {
        if ($this->getSellerType() == RestaurantOrderCategory::ON_DEMAND) {
            return TimeRangeType::ON_DEMAND;
        } else {
            return TimeRangeType::WEEKLY_SPECIAL;
        }
    }

    public function isAvailableNow()
    {
        $is_avail = new IsAvailable($this);
        return $this->is_available_to_customers && $is_avail->isAvailableNow();
    }

}
