<?php

namespace App\Http\Controllers;

use App\CustomClasses\Restaurants\Sellers;
use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomTraits\HandlesTimeRanges;
use App\CustomTraits\IsAvailable;
use App\Models\Restaurant;
use App\Models\SpecialEvent;

/**
 * @test Tests\Unit\Controllers\OrderFlow\SellerEntityControllerTest
 * Class SellerEntityController
 * @package App\Http\Controllers
 */
class SellerEntityController extends Controller
{
    use HandlesTimeRanges;
    protected $cart;

    /**
     * SellerEntityController constructor.
     * @param ShoppingCart $cart
     */
    public function __construct(ShoppingCart $cart)
    {
        $this->cart = $cart;
        // detect if any previously open menu item/ restaurant
        // has recently closed
        if (!empty($items = $this->cart->checkMenuItemAndRestaurantAvailabilityAndDelete())) {
            \Session::flash('became_unavailable', $items);
        }
    }

    /**
     * @param Restaurant $rest
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showMenu(Restaurant $rest, int $id)
    {
        $restaurant = $rest->findOrFail($id);
        $menu_items = null;
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        $item_type = ItemType::RESTAURANT_ITEM;
        $is_weekly_special = $restaurant->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL);
        return view('orderFlow.showMenu',
            compact('restaurant', 'menu_items', 'item_type', 'is_weekly_special'));
    }

    public function list_restaurants(Sellers $sellers)
    {
        $on_demand_is_available = Shift::onDemandIsAvailable();
        $time_till_next_shift = Shift::diffBetweenNowAndNextShift();
        $on_demand_not_available_msg = $this->onDemandNotAvailableMsg($time_till_next_shift);
        return view('orderFlow.list_restaurants',
            compact('sellers', 'on_demand_is_available', 'on_demand_not_available_msg'));
    }

    public function showEventItems(SpecialEvent $event, int $event_id)
    {
        $event = $event->findOrFail($event_id);
        $item_type = ItemType::EVENT_ITEM;
        return view('orderFlow.show_event_items', compact('event', 'item_type'));
    }
}
