<?php

namespace Tests\Unit\Cart;

use App\CustomClasses\ShoppingCart\OnDemandBilling;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\Accessory;
use App\TestTraits\HandlesCartItems;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OnDemandBillingTest extends TestCase
{
    use DatabaseMigrations, HandlesCartItems;

    /**
     * Make sure it calculates the delivery fee correctly for on demand items
     * @test
     */
    public function itCalculatesTheDeliveryFee()
    {
        $cart = new ShoppingCart();
        $billing = new OnDemandBilling($cart);
        self::assertEquals(0, $billing->getDeliveryFee());
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 1);
        $item1 = $this->makeMenuItem(2, $rest1->id);
        $rest2 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $item2 = $this->makeMenuItem(3, $rest2->id);
        $rest3 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 5);
        $item3 = $this->makeMenuItem(3, $rest3->id);
        $items = [$item1, $item2, $item3];
        $cart_items = $this->toCartItems($items);
        $cart->putItems([$cart_items[0]]);
        $billing = new OnDemandBilling($cart);
        self::assertEquals(2, $billing->getDeliveryFee());

        $cart->putItems([$cart_items[1], $cart_items[2]]);
        $billing = new OnDemandBilling($cart);
        // we inserted items with a restaurant that had a courier payment of 5,
        // so we expect that the delivery fee is one more than that (the max of the restaurants courier payments + 1)
        // + $billing->getExtraFeeCostPerItem() cost from the extra fee for > $billing->getMaxItemsBeforeExtraFee() items
        self::assertEquals(6 + $billing->getExtraFeeCostPerItem(), $billing->getDeliveryFee());
    }

    /**
     * Make sure it calculates the cost of the food without any accessories attached
     * in the shopping cart
     * @test
     */
    public function itCalculatesCostOfOnDemandFoodWithoutAccessories()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $menu_item = $this->makeMenuItem(3);
        $menu_item2 = $this->makeMenuItem(4);
        $menu_item3 = $this->makeMenuItem(5);
        $items = [$menu_item, $menu_item2, $menu_item3];
        $cart_items = $this->toCartItems($items);
        $cart = new ShoppingCart();
        $cart->putItems([$cart_items[0]]);
        $billing = new OnDemandBilling($cart);
        self::assertEquals(3, $billing->getCostOfFood());
        $cart->putItems([$cart_items[1], $cart_items[2]]);
        $billing = new OnDemandBilling($cart);
        self::assertEquals(12, $billing->getCostOfFood());
    }

    /**
     * Assert that the on demand billing sums up the cost of food
     * even when there are multiple items with multiple accessories
     * @test
     */
    public function itCalculatesCostOfOnDemandFoodWithAccessories()
    {
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
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
        $billing = new OnDemandBilling($cart);
        // cost of first item and first acc
        self::assertEquals(5.5, $billing->getCostOfFood());
        $cart->putItems([$cart_items[1], $cart_items[2]]);
        foreach ($cart->items() as $item) {
            $cart->toggleExtra($item->getCartItemId(), $a2->id);
        }
        $billing = new OnDemandBilling($cart);
        // three items with acc2 (2.3), first item with acc1 (2.5), + cost of items
        self::assertEquals(12 + (2.3 * 3) + 2.5, $billing->getCostOfFood());
    }

    /**
     * Assert that it correctly computes the cost of the extra fee
     * @test
     */
    public function itComputesTheExtraFeeCorrectly()
    {
        $cart = new ShoppingCart();
        $billing = new OnDemandBilling($cart);
        self::assertEquals(0, $billing->getExtraFee());
        $cart_items = $this->putItemsInDB($billing->getMaxItemsBeforeExtraFee(), 2);
        $cart->putItems($cart_items);
        self::assertEquals(0, $billing->getExtraFee());
        $cart_item = $this->putItemsInDB(1, 2);
        $cart->putItems($cart_item);
        $billing = new OnDemandBilling($cart);
        self::assertEquals($billing->getExtraFeeCostPerItem(), $billing->getExtraFee());

    }

    /**
     * Assert that it computes profit for all items in the cart that
     * are from on demand restaurants.
     * The profit should be the normal delivery fee minus
     * whatever we pay the courier
     * @test
     */
    public function itCorrectlyComputesOnDemandProfit()
    {
        $cart_items = $this->putItemsInDB(2, 4); // paying courier 4
        $cart = new ShoppingCart();
        $billing = new OnDemandBilling($cart);
        // nothing in cart so profit is 0
        self::assertEquals(0, $billing->getOnDemandProfit());
        $cart->putItems($cart_items);
        $billing = new OnDemandBilling($cart);
        self::assertEquals($billing->getDeliveryFee() - $billing->getCourierPayment(), $billing->getOnDemandProfit());
        $cart->putItems($cart_items);
        self::assertEquals($billing->getDeliveryFee() - $billing->getCourierPayment(), $billing->getOnDemandProfit());
    }

    public function itComputesCourierPayment()
    {
        $cart = new ShoppingCart();
        $billing = new OnDemandBilling($cart);
        // courier payment is 0 if the cart is empty
        self::assertEquals(0, $billing->getCourierPayment());
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 1);
        $item1 = $this->makeMenuItem(2, $rest1->id);
        $rest2 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $item2 = $this->makeMenuItem(3, $rest2->id);
        $rest3 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 5);
        $item3 = $this->makeMenuItem(3, $rest3->id);
        $items = [$item1, $item2, $item3];
        $cart_items = $this->toCartItems($items);
        $cart->putItems($cart_items);
        // should be the max courier payment given by the restaurants of the menu items in the cart
        self::assertEquals(5, $billing->getCourierPayment());
    }

}
