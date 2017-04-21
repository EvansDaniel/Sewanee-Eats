<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  MenuItemOrder extends Model
{
    public $timestamps = true;
    protected $table = "menu_items_orders";

    public function accessories()
    {
        return $this->belongsToMany('\App\Models\Accessory', 'accessories_menu_items_orders',
            'menu_items_orders_id', 'accessory_id');
    }

    // the relation to use depends on the Entity type i.e. restaurant or special event
    public function item()
    {
        if (!empty($this->menu_item_id)) {
            return $this->hasOne('\App\Models\MenuItem', 'id', 'menu_item_id');
        } else if (!empty($this->event_item_id)) {
            return $this->hasOne('\App\Models\EventItem', 'id', 'event_item_id');
        }
        return null;
    }

}
