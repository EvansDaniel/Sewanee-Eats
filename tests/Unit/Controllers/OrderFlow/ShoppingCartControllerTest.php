<?php

namespace Tests\Unit\Controllers\OrderFlow;

use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Http\Controllers\ShoppingCartController;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\TestTraits\AvailabilityMaker;
use App\TestTraits\HandlesCartItems;
use Illuminate\Http\Request;
use Tests\TestCase;

class ShoppingCartControllerTest extends TestCase
{
    use AvailabilityMaker, HandlesCartItems;

    /**
     * @test
     */
    public function itRedirectsWhenItemIsNotAvailable()
    {
        \Artisan::call('migrate');
        $cart_controller = new ShoppingCartController();
        $rest = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        $this->makeResourceAvailable($rest, 'restaurant_id');
        factory(ItemCategory::class)->create();
        $item = factory(MenuItem::class)->create();
        $req = $this->mock(Request::class);
        $req->shouldReceive('input')
            ->with('item_id')->andReturn($item->id);
        $req->shouldReceive('input')
            ->with('item_type')->andReturn(ItemType::RESTAURANT_ITEM);
        $req->shouldReceive('has')->with('quantity')->andReturn(true);
        $req->shouldReceive('input')->with('quantity')->andReturn($q = 1);
        for ($i = 1; $i <= $q; $i++) {
            $req->shouldReceive('has')->with('extras' . $i)->andReturn(true);
            $req->shouldReceive('has')->with('special_instructions' . $i)->andReturn(true);
            $req->shouldReceive('input')->with('extras' . $i)->andReturn([]);
        }
        $r = $cart_controller->loadItemIntoShoppingCart($req);
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
        $this->assertSessionHas('status_bad', 'Sorry, the item could not be added. Either this restaurant or this item is not available right now');
        // rest is not available
        $rest2 = $this->makeRestaurant(RestaurantOrderCategory::ON_DEMAND, 3);
        // set up the item's new unavailable restaurant and make the item available
        $item->restaurant_id = $rest2->id;
        $item->save();
        $this->makeResourceAvailable($item, 'menu_item_id');
        $cart_controller = new ShoppingCartController();
        // should redirect cause the item is not available
        $r = $cart_controller->loadItemIntoShoppingCart($req);
        self::assertInstanceOf('\Illuminate\Http\RedirectResponse', $r);
        $this->assertSessionHas('status_bad', 'Sorry, the item could not be added. Either this restaurant or this item is not available right now');
    }

    /**
     * @test
     */
    public function itRedirectsWhenRestaurantIsNotAvailable()
    {

    }

    /**
     * @test
     */
    public function itRedirectsWhenGivenInvalidViewParameters()
    {

    }
}
