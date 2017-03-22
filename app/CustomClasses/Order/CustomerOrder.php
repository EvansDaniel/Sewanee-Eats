<?php

namespace App\CustomClasses\ShoppingCart;

use App\Models\MenuItemOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Validator;

/**
 * Class Order Handles and saves the order, regardless of the type of order
 * @package App\CustomClasses
 */
class CustomerOrder
{
    protected $billing;
    protected $cart;
    protected $request;

    public function __construct(ShoppingCart $cart, CartBilling $billing, Request $request)
    {
        $this->cart = $cart;
        $this->billing = $billing;
        $this->request = $request;
    }

    public function orderValidation($payment_type)
    {
        // TODO: handle validation for location if on demand order request
        $rules = null;
        if ($payment_type == PaymentType::VENMO_PAYMENT) {
            $rules = array(
                'name' => 'required',
                'email_address' => 'email|required',
                'venmo_username' => 'required',
            );
        } else {
            $rules = array(
                'name' => 'required',
                'email_address' => 'email|required'
            );
        }
        return Validator::make($this->request->all(), $rules);
    }

    public function handleVenmoOrder()
    {
        $order = new Order;
        // order is open b/c they haven't paid for it yet
        $order->is_open_order = true;
        $order->venmo_username = $this->request->input('venmo_username');
        $order->payment_type = PaymentType::VENMO_PAYMENT;
        $order = $this->commonOrderSetup($order);
        $order->save();
    }

    // TODO: move stripe view error handling to controller, make this method return error codes

    private function commonOrderSetup(Order $order)
    {
        $order->email_of_customer = $this->request->input('email');
        $order->c_name = $this->request->input('c_name');
        $order->is_cancelled = false;
        $order->was_refunded = false;
        $order->is_delivered = false;
        $order = $this->handleDeliveryLocation($order);
        $order_types = $this->cart->getOrderTypes();
        $order->order_types = json_encode($order_types);
        $this->saveOrderItems();
        $this->saveOrderPriceInfo();
        return $order;
    }

    private function handleDeliveryLocation(Order $order)
    {
        if ($this->cart->hasOnDemandItems()) {
            $order->delivery_location = $this->request->input('location');
        }
        return $order;
    }

    public function saveOrderItems()
    {
        foreach ($this->cart->items() as $item) {
            $order_item = new MenuItemOrder;
        }
    }

    public function saveOrderPriceInfo()
    {

    }

    public function handleStripeOrder()
    {
        $order = new Order;
        if (env('APP_ENV') === "production") {
            Stripe::setApiKey(env('STRIPE_LIVE_SECRET_KEY'));
        } else {
            Stripe::setApiKey(env('STRIPE_TEST_SECRET_KEY'));
        }

        // Token is created using Stripe.js or Checkout!
        // Get the payment token submitted by the form:
        $token = $this->request->input('stripeToken');

        $problem_with_stripe = 'There was a problem processing your payment. Please try again';
        try {
            // Use Stripe's library to make requests...
            // Charge the user's card:
            // TODO: email the user a receipt of purchase w/ order info, could be basically the same as the employee email view
            $charge = \Stripe\Charge::create(array(
                "amount" => $this->billing->getTotal(),
                "currency" => "usd",
                "description" => "SewaneeEats Delivery Charge (includes cost of food)",
                "source" => $token
            ));
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            /*$body = $e->getJsonBody();
            $err  = $body['error'];

            print('Status is:' . $e->getHttpStatus() . "\n");
            print('Type is:' . $err['type'] . "\n");
            print('Code is:' . $err['code'] . "\n");
            // param is '' in this case
            print('Param is:' . $err['param'] . "\n");
            print('Message is:' . $err['message'] . "\n");*/
            return back()->with('status_bad', 'There was a problem processing your payment. 
            Your card may have insufficient funds or the number may be incorrect. That\'s all we know');
        } catch (\Stripe\Error\RateLimit $e) {
            \Log::info('Stripe Error: Rate Limit Exception');
            return back()->with('status_bad', $problem_with_stripe);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            \Log::info('Stripe Error: Invalid parameters were supplied to Stripe\'s API');
            return back()->with('status_bad', $problem_with_stripe);
        } catch (\Stripe\Error\Authentication $e) {
            \Log::info('Stripe Error: Stripe Authentication error');
            return back()->with('status_bad', $problem_with_stripe);
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            \Log::info('Network Error: Network communication failure with Stripe');
            return $problem_with_stripe;
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return back()->with('status_bad', $problem_with_stripe);
        }
        $order->is_open_order = false;
        $order->payment_type = PaymentType::STRIPE_PAYMENT;
        $order = $this->commonOrderSetup($order);
        $order->save();
    }
}