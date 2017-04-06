<?php

namespace App\CustomClasses\Orders;

use App\CustomClasses\Delivery\DeliveryInfo;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\MenuItemOrder;
use App\Models\Order;
use App\Models\OrderPriceInfo;
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
    protected $input;
    protected $order;

    public function __construct(ShoppingCart $cart, CartBilling $billing, $input)
    {
        $this->cart = $cart;
        $this->billing = $billing;
        $this->input = $input;
    }

    // returns the order model instance
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param $payment_type integer One of the PaymentType const integer literals
     * @return \Illuminate\Validation\Validator
     */
    public function orderValidation(Request $request, $payment_type)
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
        return Validator::make($request->all(), $rules);
    }

    /**
     * TODO: Integration Test
     */
    public function handleVenmoOrder()
    {
        $order = new Order;
        // order is open b/c they haven't paid for it yet
        $order->is_paid_for = false;
        $order->venmo_username = $this->input['venmo_username'];
        $order->payment_type = PaymentType::VENMO_PAYMENT;
        $order = $this->commonOrderSetup($order);
        $order->save();
        $this->order = $order;
        $this->saveOrderItems($order);
        // false this is not a stripe order
        $this->saveOrderPriceInfo($order, false);
    }

    private function commonOrderSetup(Order $order)
    {
        $order->email_of_customer = $this->input['email_address'];
        $order->c_name = $this->input['name'];
        $order->is_cancelled = false;
        $order->was_refunded = false;
        $order->is_delivered = false;
        $order->is_being_processed = false;
        $order_types = $this->cart->getOrderTypes();
        $order->order_types = json_encode($order_types);
        if ($order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
            // only need phone number for on demand item
            if (array_key_exists('phone_number', $this->input)) {
                $order->phone_number = $this->input['phone_number'];
            }
        }
        $order = $this->handleDeliveryLocation($order);
        $del_info = new DeliveryInfo($this->cart);
        $courier_types = $del_info->getCourierTypesForItems();
        $order->courier_types = json_encode($courier_types);
        return $order;
    }

    private function handleDeliveryLocation(Order $order)
    {
        if ($this->cart->hasOnDemandItems()) {
            $building_name = $this->input['building_name'];
            if (empty($building_name)) { // they didn't input the building name
                $order->delivery_location = $this->input['address'];
            } else { // the chose university building
                $building_name = $this->input['building_name'];
                $area_type = $this->input['area_type'];
                $room_num = $this->input['room_number'];
                $location = $building_name . ', ' . $area_type . ': ' . $room_num;
                $order->delivery_location = $location;
            }
        }
        return $order;
    }

    public function saveOrderItems(Order $order)
    {
        foreach ($this->cart->items() as $item) {
            $order_item = new MenuItemOrder;
            if ($item->isSellerType(RestaurantOrderCategory::EVENT)) {
                $order_item->event_item_id = $item->getId();
            } else {
                \Log::info($item);
                $order_item->menu_item_id = $item->getId();
            }
            $order_item->special_instructions = $item->getSi();
            $order_item->was_refunded = false;
            $order_item->order_id = $order->id;
            $order_item->save(); // save before adding accessories
            $this->saveOrderItemAccessories($item, $order_item);
        }
    }

    public function saveOrderItemAccessories(CartItem $cart_item, MenuItemOrder $menu_item_order)
    {
        // check to make sure this if statement works
        // check that we can attach accessories to this item,
        // we can only attach accessories to menu items at the moment
        if (!empty($menu_item_order->menu_item_id) && !empty($cart_item->getExtras())) {
            foreach ($cart_item->getExtras() as $extra_id) {
                $menu_item_order->accessories()->attach($extra_id);
            }
        }
    }

    public function saveOrderPriceInfo(Order $order, $is_stripe_order)
    {
        $order_price_info = new OrderPriceInfo;
        $order_price_info->order_id = $order->id;
        $order_price_info->total_price = $this->billing->getTotal();
        $order_price_info->subtotal = $this->billing->getSubtotal();
        // TODO: profit and stripe fees
        $order_price_info->stripe_fees = $this->billing->getStripeFees($is_stripe_order);
        $order_price_info->profit = $this->billing->getProfit();
        $order_price_info->cost_of_food = $this->billing->getCostOfFood();
        $order_price_info->delivery_fee = $this->billing->getDeliveryFee();
        $order_price_info->tax_charged = $this->billing->getTax();
        $order_price_info->tax_percentage = $this->billing->getTaxPercent();
        $order_price_info->save();
    }

    /**
     * TODO: Integration Test
     * @return int|string
     */
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
        $token = $this->input['stripeToken'];

        $problem_with_stripe = 'There was a problem processing your payment. Please try again';
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $this->getChargeTotal(),
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
            return 'There was a problem processing your payment.
                    Your card may have insufficient funds or the number may be incorrect. That\'s all we know';
        } catch (\Stripe\Error\RateLimit $e) {
            \Log::info('Stripe Error: Rate Limit Exception');
            return back()->with('status_bad', $problem_with_stripe);
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            \Log::info('Stripe Error: Invalid parameters were supplied to Stripe\'s API');
            return $problem_with_stripe;
        } catch (\Stripe\Error\Authentication $e) {
            \Log::info('Stripe Error: Stripe Authentication error');
            return $problem_with_stripe;
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            \Log::info('Network Error: Network communication failure with Stripe');
            return $problem_with_stripe;
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return $problem_with_stripe;
        }
        $order->is_paid_for = true;
        $order->payment_type = PaymentType::STRIPE_PAYMENT;
        $order = $this->commonOrderSetup($order);
        $order->save();
        $this->order = $order;
        $this->saveOrderItems($order);
        $this->saveOrderPriceInfo($order, true);
        return 0;
    }

    public function getChargeTotal()
    {
        return $this->billing->getTotal() * 100;
    }
}