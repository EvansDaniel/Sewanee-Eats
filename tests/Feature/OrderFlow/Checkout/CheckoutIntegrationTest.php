<?php

namespace Tests\Feature\OrderFlow\Checkout;

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\Accessory;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Tests the general integration expectations of the cart
 * That correct items show up, that correct accessories show up,
 * Class CheckoutIntegrationTest
 * @package Tests\Feature\OrderFlow\Checkout
 */
class CheckoutIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function testWeeklySpecialItemsShowUp()
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
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'restaurant_id' => $rest->id,
            'time_range_type' => TimeRangeType::WEEKLY_SPECIAL
        ]);
        $this->visit(route('checkout'))
            ->see('Your Weekly Special Items')// see the weekly special category
            ->see($cart_item->getName())
            ->see($cart_item->getPrice());
    }

    public function testAccessoriesForSpecials()
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
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'restaurant_id' => $rest->id,
            'time_range_type' => TimeRangeType::WEEKLY_SPECIAL
        ]);
        $accs = factory(Accessory::class, 5)->create();
        $acc_ids = [];
        foreach ($accs as $acc) {
            $acc_ids[] = $acc->id;
        }
        $cart->getItem($cart_item->getCartItemId())->setExtras($acc_ids);
        $cart->save();
        /*$this->visit(route('checkout'))
            ->see($accs[0]->name);*/
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


    public function makeShiftForNow()
    {
        // make a time_range that is available now
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
    }
}
