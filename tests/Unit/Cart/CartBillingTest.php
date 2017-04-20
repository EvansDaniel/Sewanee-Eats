<?php

namespace Tests\Unit\Cart;

use App\CustomClasses\ShoppingCart\CartBilling;
use App\CustomClasses\ShoppingCart\OnDemandBilling;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomClasses\ShoppingCart\SpecialBilling;
use App\TestTraits\HandlesCartItems;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CartBillingTest extends TestCase
{
    use DatabaseMigrations, HandlesCartItems;

    /**
     * @test
     */
    public function itCalculatesTotalPrice()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getTotal());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems([$cart_items[0]]);
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(round(($bill->getCostOfFood() + $bill->getDeliveryFee()) * $bill->getTaxPercent(), 2), $bill->getTotal());
    }

    /**
     * @test
     */
    public function itCalculatesTotalDiscount()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getDiscount());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems($cart_items);
        $bill = new CartBilling($s = new SpecialBilling($cart), $d = new OnDemandBilling($cart));
        self::assertEquals($s->getDiscount() + $d->getDiscount(), $bill->getDiscount());
    }

    public function itCalculatesTheTaxCorrectly()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getTax());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems($cart_items);
        $bill = new CartBilling($s = new SpecialBilling($cart), $d = new OnDemandBilling($cart));
        self::assertEquals($bill->getTotal() * ($bill->getTaxPercent() - 1), $bill->getTax());
    }

    /**
     * @test
     */
    public function itCalculatesTheTotalDeliveryFee()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getDeliveryFee());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems($cart_items);
        $bill = new CartBilling($s = new SpecialBilling($cart), $d = new OnDemandBilling($cart));
        self::assertEquals($s->getDeliveryFee() + $d->getDeliveryFee(), $bill->getDeliveryFee());
    }


    /**
     * Tests CartBilling::getCostOfFood()
     * Makes sure that it totals the cost of food correctly
     * @test
     */
    public function itCalculatesTotalCostOfFood()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getCostOfFood());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems($cart_items);
        $bill = new CartBilling($s = new SpecialBilling($cart), $d = new OnDemandBilling($cart));
        self::assertEquals($s->getCostOfFood() + $d->getCostOfFood(), $bill->getCostOfFood());
    }

    /**
     *
     * @test
     */
    public function itCalculatesSubtotal()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart));
        self::assertEquals(0, $bill->getSubtotal());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $rest1 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND);
        $m2 = $this->makeMenuItem(4, $rest1->id);
        $m3 = $this->makeMenuItem(5, $rest1->id);
        $cart_items = $this->toCartItems([$m1, $m2, $m3]);
        $cart->putItems($cart_items);
        $bill = new CartBilling($s = new SpecialBilling($cart), $d = new OnDemandBilling($cart));
        self::assertEquals($s->getCostOfFood() + $s->getDeliveryFee() + $d->getCostOfFood() + $d->getDeliveryFee(), $bill->getSubtotal());
    }

    /**
     * @test
     * It calculates the profit correctly for stripe orders
     */
    public function profitForStripeOrders()
    {
        $cart = new ShoppingCart();
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart), true); // is a stripe order
        self::assertEquals(0, $bill->getProfit());
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $m1 = $this->makeMenuItem(3, $rest->id);
        $cart_items = $this->toCartItems([$m1]);
        $cart->putItems($cart_items);
        $on_demand_bill = new OnDemandBilling($cart);
        $bill = new CartBilling(new SpecialBilling($cart), new OnDemandBilling($cart), true);
        // we use just the demand_bill profit b/c we only have an on demand restaurant
        // and we should subtract the stripe fees from that since it is a stripe order
        self::assertEquals($on_demand_bill->getOnDemandProfit() - $bill->getStripeFees(), $bill->getProfit());
    }
}
