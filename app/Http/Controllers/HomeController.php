<?php

namespace App\Http\Controllers;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;

class HomeController extends Controller
{
    public function showHome()
    {
        return view('home.homev2');
    }

    public function showThankYou()
    {
        $new_order = \Session::get('new_order');
        $weekly_special_order_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        return view('orderFlow.thanks', compact('new_order', 'weekly_special_order_type'));
    }

    public function findMyOrder()
    {
        return view('home.find_your_order');
    }

    public function orderSummary($order_id)
    {
        $order = Order::findOrFail($order_id);
        return view('home.order_summary', compact('order'));
    }

    public function showPricing()
    {
        return view('home.pricing');
    }

    public function showHowItWorks()
    {
        return view('home.how_it_works');
    }
}
