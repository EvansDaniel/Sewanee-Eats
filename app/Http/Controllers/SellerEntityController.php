<?php

namespace App\Http\Controllers;

use App\CustomClasses\Restaurants\Sellers;
use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
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
    public function showMenu(Restaurant $rest, $id)
    {
        $restaurant = $rest->findOrFail($id);
        // if this is an on demand restaurant and we are closed right now
        if ($restaurant->isSellerType(RestaurantOrderCategory::ON_DEMAND) && empty(Shift::now())) {
            return back()->with('status_bad', 'Sorry we are currently closed and not taking On Demand orders');
        }
        if (!$restaurant->isAvailableNow()) {
            return redirect()->route('list_restaurants')->with('status_bad', 'Sorry this restaurant is not available right now');
        }
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
        $shift_now = Shift::now();
        return view('orderFlow.list_restaurants',
            compact('sellers', 'shift_now'));
    }

    public function showEventItems(SpecialEvent $event, $event_id)
    {
        $event = $event->findOrFail($event_id);
        $item_type = ItemType::EVENT_ITEM;
        return view('orderFlow.show_event_items', compact('event', 'item_type'));
    }
}
