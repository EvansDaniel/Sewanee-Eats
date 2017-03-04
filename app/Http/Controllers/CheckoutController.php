<?php

namespace App\Http\Controllers;

use App\CustomTraits\PriceInformation;
use App\Events\NewOrderReceived;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;
use App\Models\Order;
use App\Models\OrderPriceInfo;
use App\User;
use Event;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    use PriceInformation;

    public function showCheckoutPage()
    {
        // nothing in cart so redirect to the home page
        $subtotal = $this->getSubTotal();
        // TODO: dynamically fill in location that gets passed to getTotalPrice
        $total_price = $this->getTotalPrice($subtotal);

        $items = $this->categorizedItems();
        return view('orderFlow.checkout', compact('total_price', 'subtotal', 'items'));
    }


    public function testEmail()
    {
        $user = User::findOrFail(\Auth::id());
        $items = MenuItem::all()->take(5);
        \Mail::send('emails.new_order', compact('items'), function ($message) use ($user) {
            $message->from('sewaneeeats@gmail.com');
            $message->to($user->email, $user->name)->subject('New Order Request!');
        });
    }

    public function handleCheckout(Request $request)
    {
        /*
         * Check that there is someone available to service order request
         * (possibly at the checkout page so that they can’t hit the submit button)
         * Check further that there is enough deliverers to handle the request
         */
        // $available_couriers = User::find(\Auth::id())->first();
        $pay_with_venmo = $request->input('pay_with_venmo');
        $email = $request->input('email_address');
        $location = $request->input('location');

        if (!empty($location)) {
            // blah blah
        }
        // attach all menu_items in the menu_items_orders table, attach whether it is special_item here????
        // other option is to create two different orders, splitting the on demand and the weekly special
        // but give the illusion to user that it is all one order??? -> I like this idea
        // insert all pricing info into create_order_price_info table
        $items = $this->categorizedItems(true);

        // TODO: need this same thing for non special items
        if (!empty($items['special_items'])) {
            // submit a weekly special order
            $special_menu_item_orders = [];
            $weekly_special_order = new Order;
            // set up order stuff
            $weekly_special_order->is_open_order = true;
            $weekly_special_order->delivery_location = null;
            $weekly_special_order->email_of_customer = $email;
            $weekly_special_order->is_weekly_special = true;
            $weekly_special_order->was_refunded = false;
            $weekly_special_order->paid_with_venmo = $pay_with_venmo;
            $weekly_special_order->has_paid_with_venmo = false;
            $weekly_special_order->save();

            $item = 0;
            foreach ($items['special_items'] as $special_cart_item) {
                $menu_item_order = new MenuItemOrder;
                // the itemth index corresponds the the correct instruction and extras array
                $menu_item_order->special_instructions = $special_cart_item['special_instructions'][$item];
                $menu_item_order->order_id = $weekly_special_order->id;
                $menu_item_order->menu_item_id = $special_cart_item['menu_item_model']->id;
                // save BEFORE attaching
                $menu_item_order->save();
                // attach all the accessories to the menu item
                if (!empty($special_cart_item['extras'][$item])) {
                    foreach ($special_cart_item['extras'][$item] as $acc) {
                        $menu_item_order->accessories()->attach($acc);
                    }
                }
                $special_menu_item_orders[] = $menu_item_order;
                $item++;
            }

            // save this order's menu items with special instructions
            // extras are related above
            $weekly_special_order->menuItemOrders()->saveMany($special_menu_item_orders);

            // handle price information saving
            // this will be created at order creation time
            $this->saveOrderPriceInfo($weekly_special_order);

            Event::fire(new NewOrderReceived($weekly_special_order));
        }

        if ($pay_with_venmo == 1) { // user selected to pay with venmo
            $v_username = $request->input('venmo_username');
            // send user an email
        } else {
            // Stripe stuff

            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            Stripe::setApiKey("sk_test_q1G3EAy0kSxeIdRYzqZy78ca");

            // Token is created using Stripe.js or Checkout!
            // Get the payment token submitted by the form:
            $token = $request->input('stripeToken');

            // Charge the user's card:
            // TODO: email the user a receipt of purchase w/ order info, could be basically the same as the courier email view
            $charge = \Stripe\Charge::create(array(
                "amount" => 0,
                "currency" => "usd",
                "receipt_email" => "evansdb0@sewanee.edu",
                "description" => "SewaneeEats Delivery Charge (includes cost of food)",
                "source" => $token
            ));
        }

        //Event::fire(new NewOrderReceived($order));
        return back()->with('status', 'Your order has been submitted');
    }

    private function saveOrderPriceInfo($order)
    {
        $priceInfo = new OrderPriceInfo;
        $priceInfo->order_id = $order->id;
        if ($order->is_weekly_special) {
            $profit = $this->getSpecialItemsFees($order->menuItemOrders);
            $priceInfo->profit = $profit;
            $subtotal = $this->foodCostOfSpecialItems() + $profit;
            $priceInfo->subtotal = $subtotal;
            $priceInfo->total_price = $subtotal * $this->getStateTax();
            \Log::info($priceInfo->totalPrice);
        } else {
            $profit = $this->getNonSpecialItemFees();
            $priceInfo->profit = $profit;
            $subtotal = $this->foodCostOfNonSpecialItems() + $profit;
            $priceInfo->subtotal = $subtotal;
            $priceInfo->total_price = $subtotal * $this->getStateTax();
        }
        $priceInfo->state_tax = ($subtotal * ($this->getStateTax() - 1));
        $priceInfo->save();
    }
}
