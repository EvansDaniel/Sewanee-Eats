<?php

namespace Tests\Feature\OrderFlow;

use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomTraits\HandlesTimeRanges;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\TimeRange;
use App\TestTraits\AvailabilityMaker;
use App\TestTraits\HandlesCartItems;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SellerEntityControllerIntegrationTest extends TestCase
{
    use DatabaseMigrations, HandlesTimeRanges, AvailabilityMaker, HandlesCartItems;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function itShowsTheMenuItemsForOnDemandRestaurant()
    {
        $this->makeShiftAndRestAvailableNow(RestaurantOrderCategory::ON_DEMAND);
        $on_demand_rest = Restaurant::where('seller_type', RestaurantOrderCategory::ON_DEMAND)->first(); // eloquent is already tested
        factory(ItemCategory::class)->create(['name' => 'Sandwiches']);
        factory(MenuItem::class)->create(['name' => 'My Menu Item', 'restaurant_id' => $on_demand_rest->id]);
        $this->visit(route('showMenu', ['name' => cleanseRestName($on_demand_rest->name)]))
            ->see('My Menu Item')
            ->see('Sandwiches');
    }

    /**
     * Assert that the $is_weekly_special variable attached to the showMenu
     * routes returned view has the correct value
     * @test
     */
    public function assertViewHasCorrectRestaurantTypeBooleanAndHasAnItemType()
    {
        $this->makeShiftAndRestAvailableNow(RestaurantOrderCategory::ON_DEMAND);
        $on_demand_rest = Restaurant::where('seller_type', RestaurantOrderCategory::ON_DEMAND)->first(); // eloquent is already tested
        factory(ItemCategory::class)->create(['name' => 'Sandwiches']);
        factory(MenuItem::class)->create(['name' => 'My Menu Item', 'restaurant_id' => $on_demand_rest->id]);
        $this->visit(route('showMenu', ['name' => cleanseRestName($on_demand_rest->name)]))
            ->assertViewHas('is_weekly_special', false)
            ->assertViewHas('item_type');
    }

    /**
     * When a menu item expires while being in the cart of a user, the item
     * will be auto removed and the user will be shown the names of the items
     * and item's restaurants that were removed
     * @test
     */
    public function assertViewContainsMenuItemNamesOfItemsAutoRemoved()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $this->makeResourceAvailable($rest, 'restaurant_id');
        $is = new IsAvailable($rest);
        factory(ItemCategory::class)->create(['name' => 'Sandwiches']);
        $menu_item = factory(MenuItem::class)->create(['name' => 'My Menu Item', 'restaurant_id' => $rest->id]);
        // make a time range that has expired (is in the past)
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(2)->format('l'),
            'end_dow' => Carbon::now()->subHour()->format('l'),
            'start_hour' => Carbon::now()->subHours(2)->hour,
            'end_hour' => Carbon::now()->subHour()->hour,
            'menu_item_id' => $menu_item->id,
            'time_range_type' => TimeRangeType::MENU_ITEM
        ]);
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart = new ShoppingCart();
        $cart->putItems([$cart_item]);
        $this->visit(route('showMenu', ['name' => cleanseRestName($rest->name)]))
            ->see($cart_item->getName() . ' from ' . $cart_item->getSellerEntity()->name);
    }

    /**
     * Check that it lists on demand restaurants and lists them as open
     * when it is in fact open and we are taking on demand restaurants
     * (there is a shift right now)
     * @test
     */
    public function listsOnDemandRestsAsOpen()
    {
        $rest = $this->makeShiftAndRestAvailableNow(RestaurantOrderCategory::ON_DEMAND);
        $this->visit(route('list_restaurants'))
            ->see($rest->image_url)
            ->see('OPEN');
    }

    /**
     * When a menu item isn't available right now, it the customer should be told
     * using the text in the test
     * @test
     */
    public function assertThatMenuItemIsCorrectlyUnavailable()
    {
        $rest = $this->makeShiftAndRestAvailableNow(RestaurantOrderCategory::ON_DEMAND);
        $on_demand_rest = Restaurant::where('seller_type', RestaurantOrderCategory::ON_DEMAND)->first(); // eloquent is already tested
        factory(ItemCategory::class)->create(['name' => 'Sandwiches']);
        $menu_item = factory(MenuItem::class)->create(['name' => 'My Menu Item', 'restaurant_id' => $on_demand_rest->id]);
        // make a time range that has expired (is in the past)
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(2)->format('l'),
            'end_dow' => Carbon::now()->subHour()->format('l'),
            'start_hour' => Carbon::now()->subHours(2)->hour,
            'end_hour' => Carbon::now()->subHour()->hour,
            'menu_item_id' => $menu_item->id,
            'time_range_type' => TimeRangeType::MENU_ITEM
        ]);
        // The above code is boiler plate for creating an on demand rest
        // that is available with a shift to service it and one menu item that
        // IS NOT available right now

        // this asserts that the view shows the menu item as not available
        $this->visit(route('showMenu', ['name' => cleanseRestName($rest->name)]))
            ->see('This item is not available right now');
    }

    /**
     * @test
     */
    public function assertThatUnavailableSpecialRestsAreSeen()
    {
        // MAKE A weekly special restaurant that is available now
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL,
            'is_available_to_customers' => true
        ]);
        // make a time_range that is available now and associate to restaurant
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(2)->format('l'),
            'end_dow' => Carbon::now()->subHour()->format('l'),
            'start_hour' => Carbon::now()->subHours(2)->hour,
            'end_hour' => Carbon::now()->subHour()->hour,
            'restaurant_id' => $rest->id,
            'time_range_type' => TimeRangeType::WEEKLY_SPECIAL
        ]);
        $this->visit(route('list_restaurants'))
            ->see($rest->image_url);
    }

    /**
     * When a weekly special restaurant is available to customers,
     * it is shown on the restaurants listing
     */
    public function testThatAvailableSpecialRestsAreNotHidden()
    {
        // make it so that shift now is true
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL,
            'is_available_to_customers' => true
        ]);
        // make a time_range that is available now
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(2)->format('l'),
            'end_dow' => Carbon::now()->addHours(2)->format('l'),
            'start_hour' => Carbon::now()->subHours(2)->hour,
            'end_hour' => Carbon::now()->addHours(2)->hour,
            'restaurant_id' => $rest->id,
            'time_range_type' => TimeRangeType::WEEKLY_SPECIAL
        ]);
        $this->visit(route('list_restaurants'))
            ->see($rest->image_url);
    }

    /**
     * assert that a restaurant is not shown on the restaurant listing
     * when it is not available to customers
     * @test
     */
    public function restDoesNotShowUpWhenNotAvailableToCustomers()
    {
        // make it so that shift now is true
        $rest = factory(Restaurant::class)->create([
            'seller_type' => RestaurantOrderCategory::ON_DEMAND,
            'is_available_to_customers' => false
        ]);
        // make a time_range that is available now
        $time_range = factory(TimeRange::class)->create([
            'start_dow' => Carbon::now()->subHours(3)->format('l'),
            'end_dow' => Carbon::now()->addHour(4)->format('l'),
            'start_hour' => Carbon::now()->subHours(3)->hour,
            'end_hour' => Carbon::now()->addHours(4)->hour,
            'time_range_type' => TimeRangeType::SHIFT
        ]);
        $this->visit(route('list_restaurants'))
            ->dontSee($rest->image_url);
    }
}
