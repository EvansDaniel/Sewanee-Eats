<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function showHome()
    {
        return view('home.home');
    }

    public function showPricing()
    {
        return view('home.pricing');
    }

    public function showSupport()
    {
        return view('home.support');
    }

    public function showHowItWorks()
    {
        return view('home.how_it_works');
    }

    public function showFindYourOrder()
    {
        return view('home.find_your_order');
    }
}
