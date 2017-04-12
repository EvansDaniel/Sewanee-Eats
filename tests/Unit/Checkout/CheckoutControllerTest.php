<?php

namespace Tests\Unit\Checkout;

use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\CheckoutController;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\TestTraits\AvailabilityMaker;
use App\TestTraits\HandlesCartItems;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use HandlesCartItems, AvailabilityMaker;

    /**
     * If the user goes to the checkout and an item expired,
     * it will show that to the user
     * @test
     */
    public function flashesExpiredCartItemsToSession()
    {
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->once()->andReturn(['item']);
        $cart->shouldReceive('getSpecialItems')->andReturn([]);
        $bill = $this->mock(CartBilling::class);
        $cc = new CheckoutController();
        $cc->showCheckoutPage($cart, $bill);
        $this->assertSessionHas('became_unavailable', ['item']);
    }

    /**
     * @test
     */
    public function redirectOnEmptyCart()
    {
        $cc = new CheckoutController();
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive('items')
            ->andReturn([]);
        $bill = $this->mock(CartBilling::class);
        $r = $cc->handleCheckout($bill, $cart, new Request());
        $this->assertSessionHas('status_bad', 'There are no items in your cart. Start your order here');
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
        self::assertEquals($r->getTargetUrl(), route('list_restaurants'));
    }

    /**
     * Tests that when the view passes the parameter payment_type parameter
     * to the controller that the correct methods are called on the CustomerOrder
     * class
     * @test
     */
    public function assertHandleVenmoIsCalledWithProperViewParameter()
    {
        $cc = new CheckoutController();
        $customer_order = $this->mock(CustomerOrder::class);
        $validator = $this->mock(Validator::class);
        // get through validation
        $validator->shouldReceive('fails')->andReturn(false);
        $customer_order->shouldReceive('orderValidation')
            ->with(PaymentType::VENMO_PAYMENT)
            ->andReturn($validator);
        $customer_order->shouldReceive('handleVenmoOrder')->once();
        $customer_order = $cc->handleNewOrder($customer_order, 1); // this is a venmo order
        self::assertInstanceOf(CustomerOrder::class, $customer_order);
    }

    /**
     * Tests that when the view passes the parameter payment_type parameter
     * to the controller that the correct methods are called on the CustomerOrder
     * class
     * @test
     */
    public function assertHandleStripeIsCalledWithProperViewParameter()
    {
        $cc = new CheckoutController();
        $customer_order = $this->mock(CustomerOrder::class);
        $validator = $this->mock(Validator::class);
        // get through validation
        $validator->shouldReceive('fails')->andReturn(false);
        $customer_order->shouldReceive('orderValidation')->once()
            ->with(PaymentType::STRIPE_PAYMENT)
            ->andReturn($validator);
        $customer_order->shouldReceive('handleStripeOrder')->once()
            ->andReturn(null); // no error message from stripe
        $customer_order = $cc->handleNewOrder($customer_order, 0); // this is a venmo order
        self::assertInstanceOf(CustomerOrder::class, $customer_order);
    }

    /**
     * When the user checkouts with stripe payment, assert that
     * if the payment was invalid in some way that the user is redirected
     * back the to the checkout page with the error msg returned from the
     * handleStripeOrder function
     * @test
     */
    public function assertRedirectedBackToCheckoutOnStripeError()
    {

        \Artisan::call('migrate');
        // boiler plate to pass the initial checks for the checkout
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        factory(ItemCategory::class)->create();
        $menu_item = factory(MenuItem::class)->create();
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart = new ShoppingCart();
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $cart->putItems([$cart_item]);
        $cc = new CheckoutController();
        $customer_order = $this->mock(CustomerOrder::class);
        $validator = $this->mock(Validator::class);
        // get through validation
        $validator->shouldReceive('fails')->andReturn(false);
        $customer_order->shouldReceive('orderValidation')->once()
            ->with(PaymentType::STRIPE_PAYMENT)
            ->andReturn($validator);
        $customer_order->shouldReceive('handleStripeOrder')->once()
            ->andReturn($blah = 'blah'); // no error message from stripe
        // simulate visiting the checkout prior to being redirected
        $this->visit(route('checkout'));
        $r = $cc->handleNewOrder($customer_order, 0); // this is a venmo order
        $this->assertSessionHas('status_bad', $blah);
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
        self::assertEquals($r->getTargetUrl(), route('checkout'));
    }

    /**
     * @test
     */
    public function redirectsToCheckoutWithFlashedExpiredItems()
    {
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive('items')->once()->andReturn(['item', 'item']);
        $cart->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->once()->andReturn(['item']);
        $bill = $this->mock(CartBilling::class);
        $cc = new CheckoutController();
        $req = $this->mock(Request::class);
        $r = $cc->handleCheckout($bill, $cart, $req);
        $this->assertSessionHas('became_unavailable', ['item']);
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
        self::assertEquals($r->getTargetUrl(), route('checkout'));
    }

    /**
     * If the user meddles with the payment parameter, the controller
     * will redirect to the checkout page
     * @test
     */
    public function assertRedirectsOnInvalidViewPaymentParameter()
    {
        \Artisan::call('migrate');
        // boiler plate to pass the initial checks to go to the checkout
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        factory(ItemCategory::class)->create();
        $menu_item = factory(MenuItem::class)->create();
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart = new ShoppingCart();
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $cart->putItems([$cart_item]);
        // simulate going to checkout before being redirected
        $this->visit(route('checkout'))
            ->seePageIs(route('checkout'));
        // Build mocks of the objects needed to call handleCheckout on
        // the Checkout Controller
        $cart = $this->mock(ShoppingCart::class);
        $cart->shouldReceive('items')->once()->andReturn(['item', 'item']);
        $cart->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->once()->andReturn([]);
        $bill = $this->mock(CartBilling::class);
        $cc = new CheckoutController();
        $req = $this->mock(Request::class);
        $req->shouldReceive('input')
            ->with('payment_type')
            ->andReturn(3); // return an invalid payement parameter
        $r = $cc->handleCheckout($bill, $cart, $req);
        self::assertInstanceOf(RedirectResponse::class, $r);
        // redirects to checkout
        self::assertEquals($r->getTargetUrl(), route('checkout'));
        $this->assertSessionHas('status_bad',
            'Sorry there was a problem processing your order. Please try again');
    }
}
