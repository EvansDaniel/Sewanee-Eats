<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = "menu_items";


    // belongs to one category
    // belongs to one restaurant


    public function itemCategory()
    {
        return $this->belongsTo('App\Models\ItemCategory',
            'item_category_id',
            'id');
    }
}
