<?php

namespace Tests\Unit\Cart;

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomClasses\ShoppingCart\SpecialBilling;
use App\Models\Accessory;
use App\TestTraits\HandlesCartItems;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SpecialsBillingTest extends TestCase
{
    use HandlesCartItems, DatabaseMigrations;

    /**
     * Make sure it calculates the delivery fee correctly for on demand items
     * @test
     */
    public function itCalculatesTheDeliveryFee()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3);
        $m2 = $this->makeMenuItem(4);
        $m3 = $this->makeMenuItem(5);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart = new ShoppingCart();
        $billing = new SpecialBilling($cart);
        self::assertEquals(0, $billing->getDeliveryFee());
        $cart->putItems([$cart_items[0]]);
        // delivery fee only goes down AFTER first item
        $billing = new SpecialBilling($cart);
        self::assertEquals($billing->getBaseDeliveryFee(), $billing->getDeliveryFee());
        $cart->putItems([$cart_items[1], $cart_items[2]]);
        $billing = new SpecialBilling($cart);
        self::assertEquals($billing->getBaseDeliveryFee() - (2 * $billing->getDiscountValue()), $billing->getDeliveryFee());

    }

    /**
     * Assert that it computes the cost of special food in the cart
     * with no accessories
     * @test
     */
    public function itCalculatesCostOfOnDemandFoodWithoutAccessories()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL, 3);
        $menu_item = $this->makeMenuItem(3);
        $menu_item2 = $this->makeMenuItem(4);
        $menu_item3 = $this->makeMenuItem(5);
        $cart_items = $this->toCartItems([$menu_item, $menu_item2, $menu_item3]);
        $cart = new ShoppingCart();
        $cart->putItems([$cart_items[0]]);
        $billing = new SpecialBilling($cart);
        self::assertEquals(3, $billing->getCostOfFood());
        $cart->putItems([$cart_items[1], $cart_items[2]]);
        $billing = new SpecialBilling($cart);
        self::assertEquals(12, $billing->getCostOfFood());
    }

    /**
     * Assert that it computes the cost of the food correctly with
     * multiple items having multiple accessories
     * @test
     */
    public function itCalculatesCostOfSpecialsFoodWithAccessories()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL, 3);
        $a1 = factory(Accessory::class)->create(['price' => 2.5]);
        $a2 = factory(Accessory::class)->create(['price' => 2.3]);
        $menu_item = $this->makeMenuItem(3);
        $menu_item2 = $this->makeMenuItem(4);
        $menu_item3 = $this->makeMenuItem(5);
        $items = [$menu_item, $menu_item2, $menu_item3];
        foreach ($items as $item) {
            $item->accessories()->attach([$a1->id, $a2->id]);
        }
        $cart_items = $this->toCartItems($items);
        $cart = new ShoppingCart();
        $cart->putItems([$cart_items[0]]);
        foreach ($cart->items() as $item) {
            $cart->toggleExtra($item->getCartItemId(), $a1->id);
        }
        $billing = new SpecialBilling($cart);
        // cost of first item and first acc
        self::assertEquals(5.5, $billing->getCostOfFood());
        $cart->putItems([$cart_items[1], $cart_items[2]]);
        foreach ($cart->items() as $item) {
            $cart->toggleExtra($item->getCartItemId(), $a2->id);
        }
        $billing = new SpecialBilling($cart);
        // three items with acc2 (2.3), first item with acc1 (2.5), + cost of items
        self::assertEquals(12 + (2.3 * 3) + 2.5, $billing->getCostOfFood());
    }


    /**
     * Assert that it computes the profit from all special items in the cart
     * @test
     */
    public function itCorrectlyComputesSpecialsProfit()
    {
        $cart = new ShoppingCart();
        $billing = new SpecialBilling($cart);
        self::assertEquals(0, $billing->getSpecialProfit());

        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3);
        $m2 = $this->makeMenuItem(4);
        $m3 = $this->makeMenuItem(5);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        $billing = new SpecialBilling($cart);
        self::assertEquals($billing->getBaseDeliveryFee() - (2 * $billing->getDiscountValue()), $billing->getSpecialProfit());
    }


    /**
     * It correctly counts the number of items in the cart that should
     * be considered "discount" items
     * @test
     */
    public function itCountsItemsWithDiscount()
    {
        $cart = new ShoppingCart();
        $billing = new SpecialBilling($cart);
        self::assertEquals(0, $billing->countItemsWithDiscount());

        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3);
        $m2 = $this->makeMenuItem(4);
        $m3 = $this->makeMenuItem(5);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart = new ShoppingCart();
        $cart->putItems($cart_items);
        $billing = new SpecialBilling($cart);
        self::assertEquals(2, $billing->countItemsWithDiscount());
    }

}
