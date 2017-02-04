<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ManageRestaurantController extends Controller
{
    public function showRestaurants()
    {
        $rest = Restaurant::all();
        return view('admin.list_restaurants', compact('rest'));
    }

    public function showNewRestaurantForm()
    {
        return view('admin.add_restaurant');
    }

    public function showRestaurantUpdate()
    {
        return view('restaurant_update_form');
    }

    public function deleteRestaurant($id)
    {
        return back();
    }

    public function updateRestaurant(Request $request)
    {
        return back();
    }
}
