<?php

namespace App\TestTraits;

use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;

trait HandlesCartItems
{
    public function putItemsInDB(int $num_items, int $payment_to_courier)
    {
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::ON_DEMAND,
            'is_available_to_customers' => true,
            'delivery_payment_for_courier' => $payment_to_courier
        ]);
        factory(ItemCategory::class)->create();
        $menu_items = factory(MenuItem::class, $num_items)->create();
        $cart_items = [];
        foreach ($menu_items as $item) {
            $cart_items[] = new CartItem($item->id, ItemType::RESTAURANT_ITEM);
        }
        return $cart_items;
    }

    public function makeMenuItem(int $price, int $rest_id = -1)
    {
        factory(ItemCategory::class)->create();
        if ($rest_id != -1) {
            return factory(MenuItem::class)->create([
                'price' => $price,
                'restaurant_id' => $rest_id
            ]);
        } else {
            return factory(MenuItem::class)->create([
                'price' => $price,
            ]);
        }
    }

    public function makeRestaurant(int $seller_type, int $delivery_payment = -1)
    {
        return factory(Restaurant::class)->create([
            'seller_type' => $seller_type,
            'is_available_to_customers' => true,
            'delivery_payment_for_courier' => $delivery_payment == -1 ? null : $delivery_payment
        ]);
    }

    public function toCartItems(array $items)
    {
        $cart_items = [];
        foreach ($items as $item) {
            $cart_items[] = new CartItem($item->id, ItemType::RESTAURANT_ITEM);
        }
        return $cart_items;
    }
}