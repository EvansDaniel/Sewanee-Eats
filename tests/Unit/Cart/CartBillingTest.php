<?php

namespace Tests\Unit\Cart;

use App\CustomClasses\ShoppingCart\CartBilling;
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

class CartBillingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     */
    public function itCalcsTotalPrice()
    {
        // does this need a test?
    }

    /**
     *
     */
    public function itCalcsDeliveryFee()
    {
        $cart_items = $this->putItemsInDB(10, 10, 2, 2);
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        // make sure bill is correct with empty cart
        self::assertEquals($bill->getBaseDeliveryFee(), $bill->getDeliveryFee());

        // adding one item doesn't lower delivery fee
        $cart->putItems([$cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee(), $bill->getDeliveryFee());

        // having four items lowers delivery fee by 1.8
        $cart->putItems([$cart_items[0], $cart_items[0], $cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee() - 1.8, $bill->getDeliveryFee());

        // the discount on delivery is capped to 1.8 dollars off, regardless of more items
        $cart->putItems($cart_items);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee() - 1.8, $bill->getDeliveryFee());

    }

    public function putItemsInDB($menu_item_price, $event_item_price, $num_event_items, $num_menu_items)
    {
        // use weekly special b/c on demand is capped
        factory(Restaurant::class, 1)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(SpecialEvent::class, 1)->create();
        factory(ItemCategory::class, 3)->create();
        factory(MenuItem::class, $num_menu_items)->create(['price' => $menu_item_price]);
        factory(EventItem::class, $num_event_items)->create(['price' => $event_item_price]);
        $cart_items = [];
        $menu_items = MenuItem::all();
        $event_items = EventItem::all();
        // total price of menu_items = 20
        foreach ($menu_items as $menu_item) {
            $cart_items[] = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        }
        foreach ($event_items as $event_item) {
            $cart_items[] = new CartItem($event_item->id, ItemType::EVENT_ITEM);
        }
        return $cart_items;
    }

    /**
     *
     */
    public function itCalcsDiscount()
    {
        $cart_items = $this->putItemsInDB(10, 10, 2, 2);
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        // make sure bill is correct with empty cart
        self::assertEquals(0, $bill->getDiscount());

        // this puts two items in the cart since $cart_items contains 4 items
        $cart->putItems(array_slice($cart_items, 2));
        $bill = new CartBilling($cart);
        self::assertEquals(40, $bill->getDiscount());

        // put one more item to test the boundary case, >= 3 items should be 60% discount
        $cart->putItems([$cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals(60, $bill->getDiscount());

        // make sure discount caps at 60%
        $cart->putItems($cart_items);
        $bill = new CartBilling($cart);
        self::assertEquals(60, $bill->getDiscount());
    }

    /**
     * Tests CartBilling::getCostOfFood()
     * Makes sure that it totals the cost of food correctly
     *
     */
    public function itCalcsCostOfFood()
    {
        $cart_items = $this->putItemsInDB(10, 10, 2, 2);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        $bill = new CartBilling($cart);
        self::assertEquals(40, $bill->getCostOfFood());
    }

    /**
     * Tests CartBilling::getSubtotal
     * Ensures it calculates the subtotal, which is the sum of the cost of and deduction of
     * all expenses and discounts
     *
     */
    public function itCalcsSubtotal()
    {
        // cost of food is 40 for all items
        $cart_items = $this->putItemsInDB(10, 10, 1, 0);
        $cart = new ShoppingCart();
        $bill = new CartBilling($cart);
        // cost should only be the delivery fee for empty cart
        self::assertEquals($bill->getDeliveryFee(), $bill->getSubtotal());

        // cost should be cost of the item and normal delivery fee
        $cart->putItems([$cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee() + $cart_items[0]->getPrice(), $bill->getSubtotal());

        // cost should be cost of the item and normal delivery fee - .6 cents
        $cart->putItems([$cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee() - .6 + $cart_items[0]->getPrice() * 2, $bill->getSubtotal());

        // cost should be cost of the item and normal delivery fee - 1.8 dollars
        $cart->putItems([$cart_items[0], $cart_items[0]]);
        $bill = new CartBilling($cart);
        self::assertEquals($bill->getBaseDeliveryFee() - 1.8 + $cart_items[0]->getPrice() * 4, $bill->getSubtotal());

    }

    /**
     * Makes sure that it adds up the accessory price when adding
     * up the cost of food
     *
     */
    public function itTakesIntoAccountTheAccessoryPrice()
    {
        factory(Restaurant::class, 1)->create();
        factory(ItemCategory::class, 3)->create();
        factory(MenuItem::class, 2)->create(['price' => 10]);
        factory(Accessory::class, 2)->create(['price' => 2]);
        $cart_items = [];
        $menu_items = MenuItem::all();
        $accs = Accessory::all();
        foreach ($menu_items as $menu_item) {
            foreach ($accs as $acc) {
                $menu_item->accessories()->attach($acc->id);
            }
            $cart_items[] = new CartItem($menu_item->id, ItemType::RESTAURANT_ITEM);
        }
        $cart = new ShoppingCart();
        // add 20 dollars worth of items
        $cart->putItems($cart_items);
        // add a 2 dollar accessory to item 1
        $cart->toggleExtra($cart_items[0]->getCartItemId(), $accs[0]->id);
        $bill = new CartBilling($cart);
        self::assertEquals(22, $bill->getCostOfFood());
        // add another 2 dollar accessory to item 2
        $cart->toggleExtra($cart_items[1]->getCartItemId(), $accs[1]->id);
        $bill = new CartBilling($cart);
        self::assertEquals(24, $bill->getCostOfFood());
        // remove the first item from the cart (removes that item's price and the 1 acc)
        foreach ($cart_items as $cart_item) {
            $cart->deleteItem($cart_item->getCartItemId());
            break;
        }
        $bill = new CartBilling($cart);
        self::assertEquals(12, $bill->getCostOfFood());

    }
}
