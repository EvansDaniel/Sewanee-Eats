<?php

namespace Tests\Unit\Order;

use App\CustomClasses\Orders\CustomerOrder;
use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\EventItem;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\SpecialEvent;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function testConstructor()
    {
        $cart = new ShoppingCart();
        $this->putMenuAndEventItemsInDB($cart->getMaxOnDemandItems(), false);
        $billing = new CartBilling($cart);
        $customer_order = new CustomerOrder($cart, $billing, []);
        $this->assertAttributeNotEmpty('cart', $customer_order);
        $this->assertAttributeNotEmpty('billing', $customer_order);
        $this->assertAttributeEmpty('input', $customer_order);
    }

    /**
     * Helper Function
     * returns twice the number of CartItems that is passed as argument
     * @param $num_event_and_menu_items integer number of menu items and event items to return
     * @return array CartItems that have $num_event_and_menu_items EventItems
     * and $num_event_and_menu_items MenuItems
     */
    private function putMenuAndEventItemsInDB($num_event_and_menu_items, $weekly)
    {
        // use weekly special b/c on demand is capped
        if ($weekly)
            factory(Restaurant::class)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
        else
            factory(Restaurant::class)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
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

    public function testSaveOrderPriceInfo()
    {
        //$orde
    }


    /*private function makeInput($has_address,$is_venmo)
    {
        if($is_venmo) {
            $input['venmo_username'] = 'venmo_username';
        } else {

        }
        $input['email_address'] = 'email';
        $input['name'] = 'name';
        $input['phone_number'] = 'phone_number';
        if($has_address) {
            $input['address'] = 'address';
        } else {
            $input['building_name'] = 'email';
            $input['area_type'] = 'area_type';
            $input['room_number'] = 'room_number';
        }
    }*/

    private function makeOrder()
    {

    }
}
