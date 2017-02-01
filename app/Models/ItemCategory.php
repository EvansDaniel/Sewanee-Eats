<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $table = "item_categories";


    // has many menu items

    public function menuItems()
    {
        return $this->hasMany('App\Models\MenuItem',
            'item_category_id','id');
    }
}
