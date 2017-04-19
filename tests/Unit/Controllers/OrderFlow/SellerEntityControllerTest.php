<?php

namespace Tests\Unit\Controllers\OrderFlow;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\SellerEntityController;
use App\Models\Restaurant;
use App\TestTraits\AvailabilityMaker;
use App\TestTraits\HandlesCartItems;
use Illuminate\Http\Request;
use Tests\TestCase;

class SellerEntityControllerTest extends TestCase
{
    use AvailabilityMaker, HandlesCartItems;
    /**
     * @test
     * Assert that if a menu item in the cart expires that the user is redirected and a
     * message is flashed pertaining to the auto removed items
     */
    public function itFlashesBecameUnavailableOnAutoCartRemoval()
    {
        $cart_mock = $this->mock(ShoppingCart::class);
        $cart_mock->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->once()->andReturn(['blah']);
        $seller_entity_controller = new SellerEntityController($cart_mock);
        $this->assertSessionHas('became_unavailable', ['blah']);
    }

    /**
     * @test
     */
    public function itDoesNotRedirectOnOnDemandNonAvailability()
    {
        \Artisan::call('migrate');
        // make a weekly special restaurant and not on demand
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $cart_mock = $this->mock(ShoppingCart::class);
        $cart_mock->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->andReturn(false);
        $seller_entity_controller = new SellerEntityController($cart_mock);
        $rest_mock = $this->mock(Restaurant::class);
        $rest_mock->shouldReceive(['where' => $rest]);
        $request = $this->mock(Request::class);
        $request->shouldReceive('query')->andReturn(null);
        $showMenuRet = $seller_entity_controller->showMenu($rest_mock, cleanseRestName($rest->name), $request);
        self::assertNotInstanceOf('\Illuminate\Http\RedirectResponse', $showMenuRet);
    }

    /**
     * @test
     */
    public function redirectsOnRestaurantNonAvailability()
    {
        \Artisan::call('migrate');
        // make a weekly special restaurant and not on demand
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $cart_mock = $this->mock(ShoppingCart::class);
        $cart_mock->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->andReturn(false);
        $seller_entity_controller = new SellerEntityController($cart_mock);
        $request = $this->mock(Request::class);
        $request->shouldReceive('query')->andReturn(null);
        $showMenuRet = $seller_entity_controller->showMenu($rest, cleanseRestName($rest->name), $request);
        // expect that we redirect back
        self::assertNotInstanceOf('\Illuminate\Http\RedirectResponse', $showMenuRet);
    }

    /**
     * @test
     * The controller will redirect when the restaurant the user requested
     * is not available, tests for weekly special restaurants
     */
    public function itDoesNotRedirectOnSpecialRestaurantNonAvailability()
    {
        $cart_mock = $this->mock(ShoppingCart::class);
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $cart_mock->shouldReceive('checkMenuItemAndRestaurantAvailabilityAndDelete')
            ->once()->andReturn(['blah']);
        $seller_entity_controller = new SellerEntityController($cart_mock);
        $request = $this->mock(Request::class);
        $request->shouldReceive('query')->andReturn(null);
        $showMenuRet = $seller_entity_controller->showMenu($rest, cleanseRestName($rest->name), $request);
        self::assertNotInstanceOf('\Illuminate\Http\RedirectResponse', $showMenuRet);
    }
}
