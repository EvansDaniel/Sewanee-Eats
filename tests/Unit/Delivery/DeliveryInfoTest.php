<?php

namespace Tests\Unit\Delivery;

use App\CustomClasses\Delivery\DeliveryInfo;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeliveryInfoTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Make sure that it returns the correct courier type based on the
     * location of the restaurants
     * @test
     * @return void
     */
    public function itGetsCorrectCourierType()
    {
        $cart_items = $this->putMenuAndEventItemsInDB(3, RestaurantOrderCategory::ON_DEMAND); // 6 on demand items
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        $delivery_info = new DeliveryInfo($cart);
        $delivery_info->getCourierTypesForItems();
    }

    /**
     * Helper Function
     * returns twice the number of CartItems that is passed as argument
     * @param $num_event_and_menu_items integer number of menu items and event items to return
     * @return array CartItems that have $num_event_and_menu_items EventItems
     * and $num_event_and_menu_items MenuItems
     */
    private function putMenuAndEventItemsInDB($num_event_and_menu_items, $rest_type)
    {
        // use weekly special b/c on demand is capped
        factory(Restaurant::class, 1)->create(['seller_type' => $rest_type]);
        //factory(SpecialEvent::class, 1)->create();
        factory(ItemCategory::class, 3)->create();
        factory(MenuItem::class, $num_event_and_menu_items)->create();
        //factory(EventItem::class, $num_event_and_menu_items)->create();
        $menu_items = MenuItem::all();
        //$event_items = EventItem::all();
        $cart_items = [];
        for ($i = 0; $i < $num_event_and_menu_items; $i++) {
            $cart_items[] = new CartItem($menu_items[$i]->id, ItemType::RESTAURANT_ITEM);
            //$cart_items[] = new CartItem($event_items[$i]->id, ItemType::EVENT_ITEM);
        }
        return $cart_items;
    }
}
