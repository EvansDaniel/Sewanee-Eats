<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    protected $table = "accessories";

    public function menuItems()
    {
        return $this->belongsToMany('App\Models\MenuItem',
            'menu_items_accessories',
            'accessory_id', 'menu_item_id');
    }
}
