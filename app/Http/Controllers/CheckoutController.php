<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    protected $location_multiplier;
    protected $base_service_fee;

    public function __construct()
    {
        $this->location_multiplier = [
            'campus' => 1.33,
            'downtown' => 1.55
        ];
        $this->base_service_fee = 3;
    }

    public function showCheckoutPage()
    {
        $cart = Session::get('cart');
        $cost_before_fees = 0;
        foreach ($cart as $item) {
            $cost_before_fees += $item['menu_item_model']->price;
        }
        // TODO: dynamically fill in location that gets passed to getTotalPrice
        $total_price = $this->getTotalPrice($cost_before_fees, 'campus');
        return view('checkout', compact('total_price', 'cost_before_fees'));
    }

    public function getTotalPrice($sum_of_items, $location)
    {
        return $this->location_multiplier[$location] * $sum_of_items
            + $this->base_service_fee;
    }

    public function handleCheckout(Request $request)
    {
        /*
         * Check that there is someone available to service order request
         * (possibly at the checkout page so that they can’t hit the submit button)
         * Check further that there is enough deliverers to handle the request
         */


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
