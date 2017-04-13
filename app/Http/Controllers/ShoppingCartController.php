<?php

namespace App\Http\Controllers;

use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\CartItem;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomClasses\ShoppingCart\ShoppingCart;
use App\CustomTraits\HandlesTimeRanges;
use App\Models\Accessory;
use App\Models\EventItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    protected $invalid_view_paramater_msg;
    use HandlesTimeRanges;

    public function __construct()
    {
        $this->invalid_view_paramater_msg = "Sorry, there was a problem adding your item(s) to the cart. Please try again";
    }

    public function getInvalidViewParamaterMsg(): string
    {
        return $this->invalid_view_paramater_msg;
    }

    public function loadItemIntoShoppingCart(Request $request)
    {
        $item_id = $request->input('item_id');
        $item_type = $request->input('item_type');
        if ($this->isInvalidItemTypeOrItemId($item_id, $item_type)) {
            return back()->with('status_bad', $this->invalid_view_paramater_msg);
        }
        if ($this->requestHasInvalidQuantityExtrasOrSi($request)) {
            return back()->with('status_bad', $this->invalid_view_paramater_msg);
        }
        $item = $item_type == ItemType::EVENT_ITEM ? EventItem::find($item_id) : MenuItem::find($item_id);
        $rest_is_on_demand = $item->restaurant->isSellerType(RestaurantOrderCategory::ON_DEMAND);
        // if the item is not available (only for on demand) or the rest is not available
        if (($rest_is_on_demand && !$item->isAvailableNow()) || !$item->restaurant->isAvailableNow()) {
            return back()->with('status_bad',
                'Sorry, the item could not be added. Either this restaurant or this item is not available right now');
        }
        // if the there is no shift right now (only for on demand)
        if ($rest_is_on_demand && !Shift::onDemandIsAvailable()) {
            return back()->with('status_bad', $this->onDemandNotAvailableMsg());
        }
        $cart = new ShoppingCart();
        $cart_items = [];
        // loop through the quanity to be added, they are all the same item
        for ($i = 1; $i <= $request->input('quantity'); $i++) {
            $cart_item = new CartItem($item_id, $item_type);
            $cart_items[] = $cart_item;
            // save the extras off this new item
            $extra = $request->input('extras' . $i);
            // save the special instructions for this new item
            $instruct = $request->input('special_instructions' . $i);
            $cart_item->setInstructions($instruct);
            $cart_item->setExtras($extra);
        }
        if ($cart->hasOnDemandOverflow($cart_items)) {
            return back()->with('status_bad',
                'Unable to add item to the cart because you have added the max number of on demand items for this order ' . $cart->getMaxOnDemandItems());
        }
        $error_val = $cart->putItems($cart_items);
        if ($error_val == -3) { // tried to add item with too many diff restaurants
            return back()->with('status_bad', 'Adding the item would cause you to order from too many different on demand restaurants. The max is two different on demand restaurants.');
        }
        if ($error_val == -1) {
            // on demand overflow
        }
        return back()->with('status_good', 'Item added to the cart');
    }

    public function isInvalidItemTypeOrItemId(int $item_id, int $item_type)
    {
        if ($item_type == ItemType::RESTAURANT_ITEM) {
            return MenuItem::find($item_id) == null;
        } else if ($item_type == ItemType::EVENT_ITEM) {
            return EventItem::find($item_id) == null;
        }
        return true;
    }

    public function requestHasInvalidQuantityExtrasOrSi(Request $request)
    {
        if (!$request->has('quantity')) {
            return true;
        }
        $q = $request->input('quantity');
        if ($q <= 0) { // check valid quantity
            return true;
        }
        for ($i = 1; $i <= $q; $i++) {
            // check for that the correct keys exist for the number of items that they requested
            if (!$request->has('extras' . $i)) {
                continue;
            }
            $extras_array = $request->input('extras' . $i);
            // check that the accessory id was not meddled with
            // if it is not found, it was meddled with
            if (!empty($extras_array)) {
                foreach ($extras_array as $extra) {
                    if (empty(Accessory::find($extra))) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
