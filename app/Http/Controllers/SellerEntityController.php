<?php

namespace App\Http\Controllers;

use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomTraits\IsAvailable;
use App\Models\Restaurant;
use App\Models\SpecialEvent;

class SellerEntityController extends Controller
{
    use IsAvailable;
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
        $restaurant = Restaurant::find($id);
        $menu_items = null;
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        // DON'T DELETE THIS YET
        /*// check if user is trying to mix weekly special and non-weekly special restaurant items
        $remove_items = [];
        $categorized_items = $this->categorizedItems();
        $message = "";
        if((!empty($categorized_items['special_items']) && !$restaurant->is_weekly_special) ||
            (!empty($categorized_items['non_special_items']) && $restaurant->is_weekly_special))
        {

            if($restaurant->is_weekly_special) {
                $message = "Your cart contains items that are not part of the weekly special. 
                    To order items from this restaurant, please remove the following non-weekly-special items from your cart";
                $remove_items = $categorized_items['non_special_items'];
            } else {
                $message = "Your cart contains items that are only apart of the weekly special. 
                To order items from this restaurant, please remove the following items from your cart";
                $remove_items = $categorized_items['special_items'];
            }
        }*/
        $item_type = ItemType::RESTAURANT_ITEM;
        return view('orderFlow.showMenu', compact('restaurant', 'menu_items', 'remove_items', 'message', 'item_type'));
    }

    public function list_restaurants()
    {
        $all_restaurants = Restaurant::all();
        $s_restaurants = [];
        $restaurants = [];
        foreach ($all_restaurants as $restaurant) {
            if ($restaurant->seller_type == RestaurantOrderCategory::WEEKLY_SPECIAL) {
                $s_restaurants[] = $restaurant;
            } else {
                // TODO: NEED TO UNCOMMENT THIS IF STATEMENT AFTER DEVELOPMENT
                //if ($this->isAvailableNow($restaurant)) {
                $restaurants[] = $restaurant;
                //}
            }
        }
        $events = SpecialEvent::all();
        // boolean to use in the view show or not show a link
        // to a page with all the restaurants on it
        $showAllRestaurants = count($all_restaurants) > count($restaurants);
        return view('orderFlow.list_restaurants',
            compact('restaurants', 's_restaurants', 'events'));
    }

    public function showEventItems($event_id)
    {
        $event = SpecialEvent::find($event_id);
        $item_type = ItemType::EVENT_ITEM;
        return view('orderFlow.show_event_items', compact('event', 'item_type'));
    }
}
