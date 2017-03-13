<?php

namespace App\Models;

use App\Contracts\ShoppingCart\SellerEntity;
use Illuminate\Database\Eloquent\Model;

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
        // TODO: Implement getName() method.
    }

    public function getDesc()
    {
        // TODO: Implement getDesc() method.
    }

    public function getLocation()
    {
        // TODO: Implement getLocation() method.
    }

    public function getSellerType()
    {
        return $this->seller_type;
    }
}
