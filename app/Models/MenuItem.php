<?php

namespace App\Models;

use App\Contracts\ShoppingCart\Item;
use App\CustomTraits\IsAvailable;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model implements Item
{
    use IsAvailable;

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

    public function itemIsAvailable()
    {
        return $this->isAvailableNow($this);
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDesc()
    {
        return $this->description;
    }

    public function getSellerEntity()
    {
        return $this->restaurant;
    }

    public function extras()
    {
        return $this->accessories;
    }
}
