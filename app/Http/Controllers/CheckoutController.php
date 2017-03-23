<?php

namespace App\Http\Controllers;

use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    public function showCheckoutPage()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        return view('orderFlow.checkout', compact('cart', 'bill'));
    }

    // TODO: acceptance test to assert that the $request object has the correct keys
    public function handleCheckout(Request $request)
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        $view_payment_type = $request->input('payment_type');
        if (empty($view_payment_type)) {
            $view_payment_type = 0;
        }
        $new_order = new CustomerOrder($cart, $bill, $request);

        // handle the new order, todo: check order validation
        if ($view_payment_type == 1) { // venmo order
            if (!$new_order->orderValidation(PaymentType::VENMO_PAYMENT)->fails()) {
                $new_order->handleVenmoOrder();
            }
        } else if ($view_payment_type == 0) { // stripe order
            if (!$new_order->orderValidation(PaymentType::STRIPE_PAYMENT)->fails()) {
                $new_order->handleStripeOrder();
            }
        }

        //Event::fire(new NewOrderReceived($new_order->getOrder()));
        //Session::forget('cart');
        // TODO: delete this from session after leave thank you
        return redirect()->route('thankYou');
    }

}
