<?php

namespace App\Http\Controllers;

use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Events\NewOrderReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class CheckoutController extends Controller
{

    public function showCheckoutPage(ShoppingCart $cart)
    {
        if (!empty($items = $cart->checkMenuItemAndRestaurantAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
        }
        $bill = new CartBilling($cart);
        return view('orderFlow.checkout', compact('cart', 'bill'));
    }

    // TODO: acceptance test to assert that the $request object has the correct keys
    public function handleCheckout(Request $request)
    {
        $cart = new ShoppingCart();
        if (empty($cart->items())) {
            return redirect()->route('list_restaurants')->with('status_bad', 'There are no items in your cart. Start your order here');
        }
        if (!empty($items = $cart->checkMenuItemAndRestaurantAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
            return redirect()->route('checkout');
        }
        $bill = new CartBilling($cart);
        $view_payment_type = $request->input('payment_type');
        if (empty($view_payment_type)) {
            $view_payment_type = 0;
        }
        $new_order = new CustomerOrder($cart, $bill, $request->all());

        // handle the new order, todo: check order validation
        if ($view_payment_type == 1) { // venmo order
            if (!$new_order->orderValidation($request, PaymentType::VENMO_PAYMENT)->fails()) {
                $new_order->handleVenmoOrder();
            }
        } else if ($view_payment_type == 0) { // stripe order
            if (!$new_order->orderValidation($request, PaymentType::STRIPE_PAYMENT)->fails()) {
                if (!empty($err_msg = $new_order->handleStripeOrder())) {
                    return back()->with('status_bad', $err_msg);
                }
            }
        }

        Event::fire(new NewOrderReceived($new_order->getOrder()));
        \Session::forget('cart');
        \Session::put('new_order', $new_order->getOrder());
        // TODO: delete this from session after leave thank you
        return redirect()->route('thankYou')
            ->with('status_good', 'Your order has been placed and a confirmation email has been sent.');
    }

}
