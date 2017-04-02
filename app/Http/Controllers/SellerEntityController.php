<?php

namespace App\Http\Controllers;

use App\CustomClasses\Restaurants\Sellers;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomTraits\IsAvailable;
use App\Models\Restaurant;
use App\Models\SpecialEvent;

class SellerEntityController extends Controller
{
    /*
     * General Idea:
     * for the given restaurant,
     * get all the categories associated with food at that restaurant
     * make an associative array of those categories where each element
     * points to the food in that category
     * That is the object to inject into the view
     */
    public function showMenu($id)
    {
        $cart = new ShoppingCart();
        // detect if any previously open menu item/ restaurant
        // has recently closed
        if (!empty($items = $cart->checkMenuItemAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
        }
        $restaurant = Restaurant::find($id);
        $menu_items = null;
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        $item_type = ItemType::RESTAURANT_ITEM;
        $is_weekly_special = $restaurant->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL);
        return view('orderFlow.showMenu',
            compact('restaurant', 'menu_items',
                'remove_items', 'message', 'item_type', 'is_weekly_special'));
    }

    public function list_restaurants()
    {
        $cart = new ShoppingCart();
        if (!empty($items = $cart->checkMenuItemAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
        }
        $sellers = new Sellers();
        return view('orderFlow.list_restaurants',
            compact('sellers'));
    }

    public function showEventItems($event_id)
    {
        $event = SpecialEvent::find($event_id);
        $item_type = ItemType::EVENT_ITEM;
        return view('orderFlow.show_event_items', compact('event', 'item_type'));
    }
}
