<?php

namespace Tests\Feature\OrderFlow\Checkout;

use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Http\Controllers\CheckoutController;
use App\Models\Accessory;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\TimeRange;
use App\TestTraits\AvailabilityMaker;
use App\TestTraits\HandlesCartItems;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * Tests the general integration expectations of the cart
 * That correct items show up, that correct accessories show up,
 * Class CheckoutIntegrationTest
 * @package Tests\Feature\OrderFlow\Checkout
 */
class CheckoutIntegrationTest extends TestCase
{
    use DatabaseMigrations, HandlesCartItems, AvailabilityMaker;

    /**
     * @test
     */
    public function redirectOnEmptyCart()
    {
        $cc = new CheckoutController();
        $r = $cc->handleCheckout(new Request());
        $this->assertSessionHas('status_bad', 'There are no items in your cart. Start your order here');
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
    }

    public function testSpecialItemsShowUp()
    {
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL,
            'is_available_to_customers' => true
        ]);
        factory(ItemCategory::class)->create();
        $menu_item = factory(MenuItem::class)->create();
        $cart = new ShoppingCart();
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart->putItems([$cart_item]);
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $this->visit(route('checkout'))
            ->see('Your Weekly Special Items')// see the weekly special category
            ->see($cart_item->getName())
            ->see($cart_item->getPrice());
    }

    /**
     * @test
     */
    public function itShowsAccessoriesForOnDemand()
    {
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::ON_DEMAND,
            'is_available_to_customers' => true
        ]);
        factory(ItemCategory::class)->create();
        $cart = new ShoppingCart();
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $courier = $this->userAndShiftNow();
        $menu_item = factory(MenuItem::class)->create();
        $this->makeResourceAvailable($menu_item, 'menu_item_id');
        $accs1 = factory(Accessory::class, 5)->create(['price' => 5]);
        $accs2 = factory(Accessory::class, 5)->create(['price' => 0]);
        $accs = $accs1->merge($accs2);
        $acc_ids = [];
        foreach ($accs as $acc) {
            $menu_item->accessories()->attach($acc->id);
            $acc_ids[] = $acc->id;
        }
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart->putItems([$cart_item]);
        $cart->getItem($cart_item->getCartItemId())->setExtras($acc_ids);
        $cart->save();
        \Log::info($cart_item->getExtras());
        foreach ($accs as $acc) {
            $this->visit(route('checkout'))
                ->see($acc->name);
        }
    }


    /**
     * @test
     */
    public function itShowsTheAccessoriesForSpecials()
    {
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL,
            'is_available_to_customers' => true
        ]);
        factory(ItemCategory::class)->create();
        $cart = new ShoppingCart();
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'restaurant_id' => $rest->id,
            'time_range_type' => $rest->getTimeRangeType()
        ]);
        $menu_item = factory(MenuItem::class)->create();
        $accs1 = factory(Accessory::class, 5)->create(['price' => 5]);
        $accs2 = factory(Accessory::class, 5)->create(['price' => 0]);
        $accs = $accs1->merge($accs2);
        $acc_ids = [];
        foreach ($accs as $acc) {
            $menu_item->accessories()->attach($acc->id);
            $acc_ids[] = $acc->id;
        }
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart->putItems([$cart_item]);
        $cart->getItem($cart_item->getCartItemId())->setExtras($acc_ids);
        $cart->save();
        foreach ($accs as $acc) {
            $this->visit(route('checkout'))
                ->see($acc->name);
        }
    }

    /**
     * Simulate adding items to the cart
     * Make sure that things that were added to the cart
     * Show up on the checkout page
     */
    public function testOnDemandItemsShowUp()
    {

    }

    public function testPricyAccessoriesShowUp()
    {

    }

    public function testFeeAccessoriesShowUp()
    {

    }

    /**
     * @test
     */
    public function checkoutNoItemsRedirectsHome()
    {
        $this->visit(route('checkout'))
            ->seePageIs(route('checkout'));
    }

}
