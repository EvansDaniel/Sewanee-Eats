<?php

namespace App\Http\Controllers;

use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\ItemLister;
use App\CustomClasses\ShoppingCart\OnDemandBilling;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomClasses\ShoppingCart\SpecialBilling;
use App\Events\NewOrderReceived;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('redirect.NotOpen');
    }

    public function showCheckoutPage(ShoppingCart $cart, CartBilling $bill)
    {
        if (!empty($items = $cart->checkMenuItemAndRestaurantAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
        }
        $cart_lister = new ItemLister($cart);
        return view('orderFlow.checkout', compact('cart', 'bill', 'cart_lister'));
    }

    // TODO: acceptance test to assert that the $request object has the correct keys
    public function handleCheckout(CartBilling $bill, ShoppingCart $cart, Request $request)
    {
        if (empty($cart->items())) {
            return redirect()->route('list_restaurants')->with('status_bad', 'There are no items in your cart. Start your order here');
        }
        if (!empty($items = $cart->checkMenuItemAndRestaurantAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
            return redirect()->route('checkout');
        }
        $view_payment_type = $request->input('payment_type');
        if (empty($view_payment_type)) {
            $view_payment_type = 0; // is stripe order
        } else if ($view_payment_type != PaymentType::VENMO_PAYMENT) { // if not venmo order
            // if given an invalid view payment parameter, return back to checkout
            return back()->with('status_bad',
                'Sorry there was a problem processing your order. Please try again');
        }
        // TODO: integrations tests with CustomerOrder to assert valid DB state after this controller method is called
        // TODO: or find a better way to mock CustomerOrder
        // we need to tell the cart billing if this is or is not a stripe order
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart), $view_payment_type == PaymentType::STRIPE_PAYMENT);
        $new_order = CustomerOrder::withRequest($cart, $bill, $request);
        $validator = $new_order->orderValidation($view_payment_type);
        if ($validator->fails()) { // check we have a valid entry
            return back()->withErrors($validator);
        }
        $new_order = $this->handleNewOrder($new_order, $view_payment_type);
        Event::fire(new NewOrderReceived($new_order->getOrder()));
        \Session::forget('cart');
        \Session::put('new_order', $new_order->getOrder());
        // TODO: delete this from session after leave thank you
        return redirect()->route('thankYou')
            ->with('status_good', 'Your order has been placed and a confirmation email has been sent.');
    }

    public function handleNewOrder(CustomerOrder $new_order, int $view_payment_type)
    {
        if ($view_payment_type != PaymentType::STRIPE_PAYMENT && $view_payment_type != PaymentType::VENMO_PAYMENT) {
            throw new InvalidArgumentException('$view_payment_type is invalid value: 
            must be' . PaymentType::STRIPE_PAYMENT . ' or ' . PaymentType::VENMO_PAYMENT . '');
        }
        // handle the new order based on the view parameter attached that
        // was ultimately attached to the Request object
        if ($view_payment_type == 1) { // venmo order
            $new_order->handleVenmoOrder();
        } else if ($view_payment_type == 0) { // stripe order
            if (!empty($err_msg = $new_order->handleStripeOrder())) {// error with stripe if true
                return back()->with('status_bad', $err_msg);
            }
        }
        return $new_order;
    }

}
