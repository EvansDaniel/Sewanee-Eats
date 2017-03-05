<?php

namespace App\Http\Controllers;

use App\Models\Order;

class HomeController extends Controller
{
    public function showHome()
    {
        return view('home.home');
    }

    public function showThankYou()
    {
        $on_demand_order = \Session::get('on_demand_order');
        $weekly_special_order = \Session::get('weekly_special_order');
        return view('orderFlow.thanks', compact('on_demand_order', 'weekly_special_order'));
    }

    public function findMyOrder()
    {
        return view('home.find_your_order');
    }

    public function orderSummary($order_id)
    {
        $order = Order::find($order_id);
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
