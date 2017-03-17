<?php

namespace Tests\Feature;

use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\SellerType;
use App\CustomClasses\ShoppingCart\ShoppingCart;
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
        factory(Restaurant::class)->create(['seller_type' => SellerType::ON_DEMAND]);
        factory(Restaurant::class)->create(['seller_type' => SellerType::WEEKLY_SPECIAL]);
        factory(SpecialEvent::class)->create();
        factory(ItemCategory::class)->create();
        factory(MenuItem::class, $num_each)->create();
        factory(MenuItem::class, $num_each)->create();
        factory(EventItem::class, $num_each)->create();
        $cart = new ShoppingCart();
        $special_rest = Restaurant::where('seller_type', SellerType::WEEKLY_SPECIAL)->first();
        $demand_rest = Restaurant::where('seller_type', SellerType::ON_DEMAND)->first();
        $event_items = EventItem::all();
        $special_items = $special_rest->menuItems;
        // make sure that the number of on demand items is less than or equal to the max
        $demand_items = [];
        for ($i = 0; $i < min(count($demand_rest->menuItems), $cart->getMaxItemsInCart()); $i++) {
            $demand_items[] = $demand_rest->menuItems[$i];
        }
        $cart_items = [];
        for ($i = 0; $i < count($special_items); $i++) {
            $cart_items[] = new CartItem($special_items[$i]->id, ItemType::RESTAURANT_ITEM);
        }
        for ($i = 0; $i < count($demand_items); $i++) {
            $cart_items[] = new CartItem($demand_items[$i]->id, ItemType::RESTAURANT_ITEM);
        }
        for ($i = 0; $i < count($event_items); $i++) {
            $cart_items[] = new CartItem($event_items[$i]->id, ItemType::EVENT_ITEM);
        }
        $cart->putItems($cart_items);
        self::assertEquals(count($demand_items), count($cart->getOnDemandItems()));
        self::assertEquals(count($special_items), count($cart->getWeeklySpecialItems()));
        self::assertEquals(count($event_items), count($cart->getEventItems()));
    }

    /**
     * Checks that the correct quantity is calculated by the cart
     * @test
     */
    public function hasCorrectQuantity()
    {
        $cart = new ShoppingCart();
        // returns 6 cart items
        $cart_items = $this->putMenuItemsInDB(3);
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
    private function putMenuItemsInDB($num_event_and_menu_items)
    {
        factory(Restaurant::class, 1)->create();
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
    public function checkMultipleSetsOfAdditions()
    {
        $cart_items1 = $this->putMenuItemsInDB(1);
        $cart_items2 = $this->putMenuItemsInDB(1);
        $cart_items3 = $this->putMenuItemsInDB(1);
        $cart_items4 = $this->putMenuItemsInDB(1);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items1);
        self::assertEquals(2, $cart->getQuantity());
        self::assertEquals($cart_items1, $cart->items());
        $cart->putItems($cart_items2);
        self::assertEquals(4, $cart->getQuantity());

    }

    /**
     * @test
     */
    public function cartItemsHaveUniqueIds()
    {
        $cart = new ShoppingCart();
        // divide by two b/c the function returns twice that many CartItems
        $cart_items = $this->putMenuItemsInDB($cart->getMaxItemsInCart() / 2);
        $temp = $cart->putItems($cart_items);
        $cart_item_ids = [];
        foreach ($cart->items() as $cart_item) {
            $cart_item_ids[] = $cart_item->getCartItemId();
        }
        self::assertTrue(count($cart_item_ids) == count(array_unique($cart_item_ids)));
    }

    public function itStoresTheNextCartItemIdInSession()
    {

    }

    /**
     * Exercises ShoppingCart::updateInstructions function
     * Asserts that it updates the instructions
     * @test
     */
    public function itSetsInstructions()
    {
        $cart_items = $this->putMenuItemsInDB(2);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        self::assertEquals(null, $cart_items[0]->getSi());
        $cart->updateInstructions($cart_items[0]->getCartItemId(), "My Super Cool INstructions");
        self::assertEquals("My Super Cool INstructions", $cart->items()[0]->getSi());
        $cart->updateInstructions($cart_items[0]->getCartItemId(), "My Sup");
        self::assertEquals("My Sup", $cart->items()[0]->getSi());
    }

    /**
     * @test
     */
    public function itSavesTheCartToTheSession()
    {
        /* $cart_items = $this->putMenuItemsInDB(2);
         $cart = new ShoppingCart();
         self::assertEquals(null,$cart->items());
         $cart->putItems($cart_items);*/
    }

    /**
     * @test
     */
    public function itStoresNextCartItemIdInSession()
    {

    }

    public function itSetsExtras()
    {

    }

    /**
     * Checks that the cart will reject the addition of
     * a set of items if that addition will cause and On Demand overflow
     * @test
     */
    public function itCapsMaxOnDemandItems()
    {
        factory(Restaurant::class)->create(['seller_type' => SellerType::ON_DEMAND]);
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
     * Checks that the cart will reject the addition of a
     * set of items if and only if the addition would cart a Cart Max Item Overflow
     * @test
     */
    public function itCapsMaxNumberOfItems()
    {
        $cart = new ShoppingCart();
        factory(Restaurant::class)->create(['seller_type' => SellerType::WEEKLY_SPECIAL]);
        factory(ItemCategory::class)->create();
        // add one extra than the max to make sure that the cart will NOT add the extra
        factory(MenuItem::class, $cart->getMaxItemsInCart() + 1)->create();
        $cart_items = [];
        $menu_items = MenuItem::all();
        foreach ($menu_items as $menu_item) {
            $cart_items[] = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        }
        $cart->putItems($cart_items);
        // rejects all additions b/c the set is one more than the max
        self::assertEquals(0, $cart->getQuantity());
        $cart_items_slice = array_slice($cart_items, 0, $cart->getMaxItemsInCart());
        $cart->putItems($cart_items_slice);
        // adds all items to the cart b/c the set contains less than or equal to the max number of allowable items
        self::assertEquals($cart->getMaxItemsInCart(), $cart->getQuantity());
    }

    /**
     * @test
     */
    public function itGetsTheRightItem()
    {
        $cart_items = $this->putMenuItemsInDB(3);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        self::assertEquals($cart->getItem($cart->items()[0]->getCartItemId()), $cart_items[0]);
    }

    /**
     * @test
     */
    public function itInsertsItems()
    {
        $cart_items = $this->putMenuItemsInDB(3);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        self::assertEquals($cart_items[0], $cart->items()[0]);
        self::assertEquals($cart_items[1], $cart->items()[1]);
        self::assertEquals($cart_items[5], $cart->items()[5]);
    }

    public function itUpdatesInstructions()
    {

    }

    public function itDeletesItems()
    {

    }

    public function itTogglesExtras()
    {

    }
}
