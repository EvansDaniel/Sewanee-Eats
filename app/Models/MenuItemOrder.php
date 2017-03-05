<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemOrder extends Model
{
    public $timestamps = true;
    protected $table = "menu_items_orders";

    public function accessories()
    {
        return $this->belongsToMany('\App\Models\Accessory', 'accessories_menu_items_orders',
            'menu_items_orders_id', 'accessory_id');
    }

    public function menuItem()
    {
        return $this->hasOne('\App\Models\MenuItem', 'id', 'menu_item_id');
    }

}
