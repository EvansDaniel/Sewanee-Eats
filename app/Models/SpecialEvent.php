<?php

namespace App\Models;

use App\Contracts\ShoppingCart\SellerEntity;
use Illuminate\Database\Eloquent\Model;

// needs to implement seller entity b/c we need to categorize the CartItem based on the seller
class SpecialEvent extends Model implements SellerEntity
{
    // has many menu items
    public function eventItems()
    {
        return $this->hasMany('App\Models\EventItem',
            'event_id', 'id');
    }

    public function getName()
    {
        return $this->event_name;
    }

    public function getDesc()
    {
        return $this->event_description;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getSellerType()
    {
        return $this->seller_type;
    }

    public function isForProfit()
    {
        return $this->for_profit;
    }

    public function getItems()
    {
        return $this->eventItems;
    }

    public function getId()
    {
        return $this->id;
    }
}
