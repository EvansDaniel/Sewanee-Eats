<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = "menu_items";


    // belongs to one category
    // belongs to one restaurant
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant', 'restaurant_id', 'id');
    }

    public function itemCategory()
    {
        return $this->belongsTo('App\Models\ItemCategory',
            'item_category_id',
            'id');
    }
}
