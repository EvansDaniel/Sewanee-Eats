<?php

namespace Tests\Feature;

use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\Models\ItemCategory;
use App\Models\Restaurant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShoppingCartTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function itCategorizesItems()
    {

    }

    /**
     * Checks that the correct quantity is calculated by the cart
     * @test
     */
    public function hasCorrectQuantity()
    {
        $cart = new ShoppingCart();
        $this->assertEquals(0, $cart->getQuantity());
        factory(Restaurant::class)->create();
        factory(ItemCategory::class)->create();

    }

    public function cartItemsHaveUniqueIds()
    {

    }

    public function itCapsTheMaxItemsInTheCart()
    {

    }

    public function itStoresTheNextCartItemIdInSession()
    {

    }

    public function itSetsInstructions()
    {

    }

    public function itSetsExtras()
    {

    }

    public function itRemovesItemsUniformly()
    {

    }

    public function itCapsMaxOnDemandItems()
    {

    }

    public function itGetsTheRightItem()
    {

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
