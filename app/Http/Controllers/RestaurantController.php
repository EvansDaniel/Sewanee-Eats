<?php

namespace App\Http\Controllers;

use App\CustomTraits\IsAvailable;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
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
        return view('showMenu',compact('restaurant','menu_items'));
    }

    public function list_restaurants()
    {
        $all_restaurants = Restaurant::all();
        $restaurants = [];
        foreach ($all_restaurants as $restaurant) {
            // TODO: NEED TO UNCOMMENT THIS IF STATEMENT AFTER DEVELOPMENT
            //if ($this->isAvailableNow($restaurant)) {
                $restaurants[] = $restaurant;
            //}
        }
        // boolean to use in the view show or not show a link
        // to a page with all the restaurants on it
        $showAllRestaurants = count($all_restaurants) > count($restaurants);
        return view('list_restaurants', compact('restaurants'));
    }

    public function store(Request $request)
    {
        // Store the details of a new restaurant
    }

    public function update(Request $request)
    {
        // Update details of existing restaurant
    }

    public function delete($id)
    {
        // delete restaurant whose id == $id
    }
}
