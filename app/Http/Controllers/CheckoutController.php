<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Stripe\Stripe;
use DB;
class CheckoutController extends Controller
{

    public function showCheckoutPage()
    {
        // Temporary solution for showing checkout stuff
        $checkoutItems = DB::table('menu_items')->limit(2)->get();
        $sum = DB::table('menu_items')->sum('price');
        return view('checkout',compact('checkoutItems','sum'));
    }

    public function handleCheckout(Request $request)
    {
        // Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey("sk_test_fkiVDbxnDU3Fnr8CH9GlBFms");

// Token is created using Stripe.js or Checkout!
// Get the payment token submitted by the form:
        $token = $request->input('stripeToken');

        echo "<pre>";
        print_r($request->all());
        echo "</pre>";

// Charge the user's card:
        $charge = \Stripe\Charge::create(array(
            "amount" => 100,
            "currency" => "usd",
            "description" => "Sewanee Eats Charge",
            "source" => $token,
        ));
        //return back()->with('status','Your order has been submitted');
    }
}
