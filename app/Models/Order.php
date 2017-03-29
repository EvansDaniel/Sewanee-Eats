<?php

namespace App\Models;

use App\Contracts\HasItems;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements HasItems
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "orders";


    // gets the courier types that can deliver this order
    public function getCourierTypes()
    {
        return json_decode($this->courier_types, true);
    }

    public function menuItemOrders()
    {
        return $this->hasMany('App\Models\MenuItemOrder', 'order_id', 'id');
    }

    // returns array of CartItem that contains the menu items for this order
    public function items()
    {
        $items = [];
        foreach ($this->menuItemOrders as $menu_item_order) {
            $menu_item = $menu_item_order->item;
            if ($menu_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::ON_DEMAND
                || $menu_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::WEEKLY_SPECIAL
            ) {
                $items[] = new CartItem($menu_item, ItemType::RESTAURANT_ITEM);
            } else if ($menu_item->getSellerEntity()->getSellerType() == RestaurantOrderCategory::EVENT) {
                $items[] = new CartItem($menu_item, ItemType::EVENT_ITEM);
            }
        }
        return $items;
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
