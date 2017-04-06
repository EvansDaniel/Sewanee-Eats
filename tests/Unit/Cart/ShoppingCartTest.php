<?php

namespace Tests\Feature;

use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\Accessory;
use App\Models\EventItem;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\SpecialEvent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function itCategorizesItems()
    {
        $num_each = 3;
        factory(Restaurant::class)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        factory(Restaurant::class)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(SpecialEvent::class)->create();
        factory(ItemCategory::class)->create();
        factory(MenuItem::class, $num_each)->create();
        factory(MenuItem::class, $num_each)->create();
        //factory(EventItem::class, $num_each)->create();
        $cart = new ShoppingCart();
        $special_rest = Restaurant::where('seller_type', RestaurantOrderCategory::WEEKLY_SPECIAL)->first();
        $demand_rest = Restaurant::where('seller_type', RestaurantOrderCategory::ON_DEMAND)->first();
        /*$event_items = EventItem::all();*/
        $special_items = $special_rest->menuItems;
        // make sure that the number of on demand items is less than or equal to the max
        $demand_items = [];
        for ($i = 0; $i < min(count($demand_rest->menuItems), $cart->getMaxOnDemandItems()); $i++) {
            $demand_items[] = $demand_rest->menuItems[$i];
        }
        $cart_items = [];
        for ($i = 0; $i < count($special_items); $i++) {
            $cart_items[] = new CartItem($special_items[$i]->id, ItemType::RESTAURANT_ITEM);
        }
        // make sure we don't go above the max items so that we don't get a on demand overflow (which would be expected behavior)
        $num_on_demand_items = min(count($demand_items), $cart->getMaxOnDemandItems());
        for ($i = 0; $i < $num_on_demand_items; $i++) {
            $cart_items[] = new CartItem($demand_items[$i]->id, ItemType::RESTAURANT_ITEM);
        }
        /*for ($i = 0; $i < count($event_items); $i++) {
            $cart_items[] = new CartItem($event_items[$i]->id, ItemType::EVENT_ITEM);
        }*/
        $cart->putItems($cart_items);
        self::assertEquals($num_on_demand_items, count($cart->getOnDemandItems()));
        self::assertEquals(count($special_items), count($cart->getWeeklySpecialItems()));
        //self::assertEquals(count($event_items), count($cart->getEventItems()));
    }

    /**
     * Checks that the correct quantity is calculated by the cart
     * @test
     */
    public function hasCorrectQuantityForWeeklySpecial()
    {
        $cart = new ShoppingCart();
        // returns 6 cart items
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        $cart_items = $this->putMenuAndEventItemsInDB(3);
        $cart->putItems($cart_items);
        self::assertEquals(6, $cart->getQuantity());
        $cart->deleteItem($cart_items[0]->getCartItemId());
        self::assertEquals(5, $cart->getQuantity());
        $cart->deleteItem($cart_items[1]->getCartItemId());
        $cart->deleteItem($cart_items[2]->getCartItemId());
        self::assertEquals(3, $cart->getQuantity());
    }

    /**
     * Helper Function
     * returns twice the number of CartItems that is passed as argument
     * @param $num_event_and_menu_items integer number of menu items and event items to return
     * @return array CartItems that have $num_event_and_menu_items EventItems
     * and $num_event_and_menu_items MenuItems
     */
    private function putMenuAndEventItemsInDB($num_event_and_menu_items)
    {
        // use weekly special b/c on demand is capped
        factory(SpecialEvent::class, 1)->create();
        factory(ItemCategory::class, 3)->create();
        factory(MenuItem::class, $num_event_and_menu_items)->create();
        factory(EventItem::class, $num_event_and_menu_items)->create();
        $menu_items = MenuItem::all();
        $event_items = EventItem::all();
        $cart_items = [];
        for ($i = 0; $i < $num_event_and_menu_items; $i++) {
            $cart_items[] = new CartItem($menu_items[$i]->id, ItemType::RESTAURANT_ITEM);
            $cart_items[] = new CartItem($event_items[$i]->id, ItemType::EVENT_ITEM);
        }
        return $cart_items;
    }

    /**
     * @test
     */
    public function checkMultipleSetsOfAdditionsForWeeklySpecial()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        $cart_items1 = $this->putMenuAndEventItemsInDB(1);
        $cart_items2 = $this->putMenuAndEventItemsInDB(1);
        $cart_items3 = $this->putMenuAndEventItemsInDB(1);
        $cart_items4 = $this->putMenuAndEventItemsInDB(1);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items1);
        self::assertEquals(2, $cart->getQuantity());
        self::assertEquals($cart_items1, $cart->items());
        $cart->putItems($cart_items2);
        $merge = array_merge($cart_items1, $cart_items2);
        self::assertEquals(4, $cart->getQuantity());
        self::assertEquals($merge, $cart->items());
        $cart->putItems($cart_items3);
        $merge = array_merge($merge, $cart_items3);
        self::assertEquals(6, $cart->getQuantity());
        self::assertEquals($merge, $cart->items());
        $merge = array_merge($merge, $cart_items4);
        $cart->putItems($cart_items4);
        self::assertEquals(8, $cart->getQuantity());
        self::assertEquals($merge, $cart->items());

    }

    /**
     * @test
     */
    public function cartItemsHaveUniqueIds()
    {
        $cart = new ShoppingCart();
        // divide by two b/c the function returns twice that many CartItems
        /*factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);*/
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart_items = $this->putMenuAndEventItemsInDB(2);
        $cart->putItems($cart_items);
        $cart_item_ids = [];
        foreach ($cart->items() as $cart_item) {
            $cart_item_ids[] = $cart_item->getCartItemId();
        }
        self::assertEquals($cart_item_ids, array_unique($cart_item_ids));
        $cart_items_slice = array_slice($cart_items, 0, count($cart_items) / 2);
        // make sure that removing items then adding items doesn't mess up the uniqueness
        foreach ($cart_items_slice as $cart_item) {
            $cart->deleteItem($cart_item->getCartItemId());
        }
        // add same items back
        $cart->putItems($cart_items_slice);
        // get the items ids
        $cart_item_ids = [];
        foreach ($cart->items() as $cart_item) {
            $cart_item_ids[] = $cart_item->getCartItemId();
        }
        // make sure that the unique version is same as the current version
        self::assertEquals($cart_item_ids, array_unique($cart_item_ids));

    }

    /**
     * Exercises ShoppingCart::updateInstructions function
     * Asserts that it updates the instructions
     * @test
     */
    public function itUpdatesInstructions()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart_items = $this->putMenuAndEventItemsInDB(2);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        self::assertEquals(null, $cart_items[0]->getSi());
        $cart->updateInstructions($cart_items[0]->getCartItemId(), "My Super Cool INstructions");
        self::assertEquals("My Super Cool INstructions", $cart->items()[0]->getSi());
        $cart->updateInstructions($cart_items[0]->getCartItemId(), "My Sup");
        self::assertEquals("My Sup", $cart->items()[0]->getSi());
    }

    /**
     * Checks that the cart saves a 'next_cart_item_id' to the session after
     * adding items to the cart
     * @test
     */
    public function itStoresNextCartItemIdInSession()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart = new ShoppingCart();
        $cart_items = $this->putMenuAndEventItemsInDB($cart->getMaxOnDemandItems());
        $cart->putItems($cart_items);
        $this->seeInSession('next_cart_item_id');
    }

    /**
     * Test that the cart removes an accessory id when the accessory already exists
     * in the item's getExtras() array and adds the accessory id when the item doesn't
     * exist in the item's
     * @test
     */
    public function itTogglesExtra()
    {
        // SET UP DB
        factory(Restaurant::class)->create();
        factory(ItemCategory::class)->create();
        factory(MenuItem::class, 1)->create();
        factory(Accessory::class, 2)->create();
        $menu_item = MenuItem::all()->first();
        $accessories = Accessory::all();
        // attach the two accessories
        foreach ($accessories as $acc) {
            $menu_item->accessories()->attach($acc->id);
        }
        // set up shopping cart
        $cart_item = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        $cart = new ShoppingCart();
        $cart->putItems([$cart_item]);
        $first_item = $cart->getItem($cart_item->getCartItemId());
        // assert that the CartItem has no extras set at the start
        self::assertEmpty($first_item->getExtras());
        $first_acc = $accessories[0];
        $second_acc = $accessories[1];

        // item only has the first_acc
        $cart->toggleExtra($first_item->getCartItemId(), $first_acc->id);
        self::assertEquals([$first_acc->id], $first_item->getExtras());

        // item only has the first_acc and second_acc
        $cart->toggleExtra($first_item->getCartItemId(), $second_acc->id);
        self::assertEquals([$first_acc->id, $second_acc->id], $first_item->getExtras());

        // item removes second_acc, leaving only first_acc
        $cart->toggleExtra($first_item->getCartItemId(), $second_acc->id);
        self::assertEquals([$first_acc->id], $first_item->getExtras());

        // item adds back the second_acc, making it have second_acc and first_acc
        $cart->toggleExtra($first_item->getCartItemId(), $second_acc->id);
        self::assertEquals([$first_acc->id, $second_acc->id], $first_item->getExtras());

        // item removes first_acc and second_acc, leaving an empty extras array
        $cart->toggleExtra($first_item->getCartItemId(), $second_acc->id);
        $cart->toggleExtra($first_item->getCartItemId(), $first_acc->id);
        self::assertEquals([], $first_item->getExtras());
    }

    /**
     * Checks that the cart will reject the addition of
     * a set of items if that addition will cause and On Demand overflow
     * @test
     */
    public function itCapsMaxOnDemandItems()
    {
        factory(Restaurant::class)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        factory(ItemCategory::class)->create();
        factory(MenuItem::class, 20)->create();
        $cart_items = [];
        $menu_items = MenuItem::all();
        foreach ($menu_items as $menu_item) {
            $cart_items[] = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        }
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        // cart will not add any items if it causes and on demand overflow
        self::assertEquals(0, $cart->getQuantity());
        $slice_of_menu_items = array_slice($cart_items, 0, $cart->getMaxOnDemandItems());
        // adds all items b/c it is <= the max number of on demand items in the cart
        $cart->putItems($slice_of_menu_items);
        self::assertEquals($cart->getMaxOnDemandItems(), $cart->getNumOnDemandItems());
    }

    /**
     * @test
     */
    public function itGetsTheRightItem()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart = new ShoppingCart();
        $cart_items = $this->putMenuAndEventItemsInDB($cart->getMaxOnDemandItems());
        $cart->putItems($cart_items);
        self::assertEquals($cart->getItem($cart->items()[0]->getCartItemId()), $cart_items[0]);
    }

    /**
     * @test
     */
    public function itInsertsItems()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart = new ShoppingCart();
        $cart_items = $this->putMenuAndEventItemsInDB($cart->getMaxOnDemandItems());
        $cart->putItems($cart_items);
        self::assertEquals($cart_items[0], $cart->items()[0]);
        self::assertEquals($cart_items[1], $cart->items()[1]);
        self::assertEquals($cart_items[5], $cart->items()[5]);
    }

    /**
     * @test
     */
    public function itDeletesItems()
    {
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        $cart = new ShoppingCart();
        $cart_items = $this->putMenuAndEventItemsInDB($cart->getMaxOnDemandItems());
        $cart->putItems($cart_items);
        self::assertEquals($cart_items, $cart->items());
        $cart->deleteItem($cart_items[0]->getCartItemId());
        $cart_items_slice = array_slice($cart_items, 1);
        self::assertEquals($cart_items_slice, $cart->items());
        self::assertEmpty($cart->getItem($cart_items[0]->getCartItemId()));
        foreach ($cart_items_slice as $cart_item) {
            $cart->deleteItem($cart_item->getCartItemId());
        }
        self::assertEquals([], $cart->items());
    }
}
