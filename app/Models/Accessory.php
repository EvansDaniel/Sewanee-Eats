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

    public function menuItemOrders()
    {
        return $this->belongsToMany('\App\Model\MenuItemOrder', 'accessories_menu_items_orders',
            'accessory_id', 'menu_items_orders_id');
    }
}
