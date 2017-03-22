<?php

namespace App\Models;

use App\Contracts\ShoppingCart\Item;
use Illuminate\Database\Eloquent\Model;

class EventItem extends Model implements Item
{
    public function event()
    {
        return $this->belongsTo('App\Models\SpecialEvent',
            'event_id', 'id');
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function isProfitable()
    {
        return $this->for_profit;
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
        return $this->event;
    }

    public function extras()
    {
        return null;
    }
}
