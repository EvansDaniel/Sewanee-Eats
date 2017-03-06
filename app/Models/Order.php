<?php

namespace App\Models;

use App\CustomTraits\PriceInformation;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use PriceInformation;

    protected $table = "orders";

    /*public function menuItems()
    {
        return $this->belongsToMany('App\Models\MenuItem', 'menu_items_orders',
            'order_id', 'menu_item_id')
            ->withPivot('special_instructions', 'quantity');
    }*/

    public function sumPriceBeforeFees()
    {
        $price = 0;
        foreach ($this->menuItemOrders as $menuItemOrder) {
            $menu_item = MenuItem::find($menuItemOrder->menu_item_id);
            $price += $menu_item->price;
        }
        return $price;
    }

    public function menuItemOrders()
    {
        return $this->hasMany('App\Models\MenuItemOrder', 'order_id', 'id');
    }

    public function restaurants()
    {
        return $this->belongsToMany('App\Models\Restaurant', 'restaurants_orders',
            'order_id', 'restaurant_id');
    }

    public function orderPriceInfo()
    {
        return $this->hasOne('App\Models\OrderPriceInfo', 'order_id');
    }

    public function couriers()
    {
        return $this->belongsToMany('App\User', 'couriers_orders',
            'order_id', 'courier_id')->withTimestamps();
    }

    public function getProfit()
    {
        return $this->profit($this->total_price, $this->menuItems);
    }

}
