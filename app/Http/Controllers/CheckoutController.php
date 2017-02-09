<?php

namespace App\Http\Controllers;

use App\CustomTraits\PriceInformation;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    use PriceInformation;

    public function showCheckoutPage()
    {
        // nothing in cart so redirect to the home page
        $cost_before_fees = $this->priceBeforeFeesFromCart();
        // TODO: dynamically fill in location that gets passed to getTotalPrice
        $total_price = $this->getTotalPrice();
        return view('checkout', compact('total_price', 'cost_before_fees'));
    }

    public function handleCheckout(Request $request)
    {
        // TODO: Assert that there is no more than
        if (!$this->cartHasValidNumberOfItems())
            return back()->with('status_bad',
                'Your cart has too many items in it (max: ' . $this->max_items_in_cart);

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
            "amount" => $this->getTotalPrice() * 100,
            "currency" => "usd",
            "description" => "Sewanee Eats Charge",
            "source" => $token,
        ));
        //return back()->with('status','Your order has been submitted');
    }
}
