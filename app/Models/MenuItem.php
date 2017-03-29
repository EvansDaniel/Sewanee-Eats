<?php

namespace App\Models;

use App\Contracts\Availability;
use App\Contracts\ResourceTimeRange;
use App\Contracts\ShoppingCart\Item;
use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomTraits\HandlesTimeRanges;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model implements Item, Availability, ResourceTimeRange
{

    use HandlesTimeRanges;
    protected $table = "menu_items";

    // belongs to one category
    // belongs to one restaurant
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant', 'restaurant_id', 'id');
    }

    public function accessories()
    {
        return $this->belongsToMany('App\Models\Accessory',
            'menu_items_accessories',
            'menu_item_id', 'accessory_id');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'orders_menu_items',
            'menu_item_id', 'order_id');
    }

    public function itemCategory()
    {
        return $this->belongsTo('App\Models\ItemCategory',
            'item_category_id',
            'id');
    }

    public function isAvailableNow()
    {
        $isAvail = new IsAvailable($this);
        // the menu item is available with 30 min spare time
        return $isAvail->isAvailableNow(30);
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function isProfitable()
    {
        return true;
    }

    public function getDesc()
    {
        return $this->description;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSellerEntity()
    {
        return $this->restaurant;
    }

    public function extras()
    {
        return $this->accessories;
    }

    public function getId()
    {
        return $this->id;
    }

    public function timeRanges()
    {
        return $this->hasMany('App\Models\TimeRange', 'menu_item_id', 'id');
    }

    public function getAvailability()
    {
        return $this->timeRanges;
    }

    public function getResourceTimeRangesByDay($dow)
    {
        $time_range_type = $this->getTimeRangeType();
        return $this->getTimeRangesByDay($dow, $time_range_type, 'menu_item_id');
    }

    public function getTimeRangeType()
    {
        return TimeRangeType::MENU_ITEM;
    }
}
