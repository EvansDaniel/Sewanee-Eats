<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\ItemType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomTraits\HandlesTimeRanges;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Illuminate\Http\Request;

class TimeRangeController extends Controller
{
    use HandlesTimeRanges;

    public function showMultiAddItems(Restaurant $rest, $rest_id)
    {
        $restaurant = $rest->findOrFail($rest_id);
        $menu_items = null;
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        $item_type = ItemType::RESTAURANT_ITEM;
        $is_weekly_special = $restaurant->isSellerType(RestaurantOrderCategory::WEEKLY_SPECIAL);
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.time_range.multi_add_items',
            compact('restaurant', 'menu_items', 'item_type', 'is_weekly_special', 'day_of_week_names'));
    }

    public function createTimeRangeMultiItems(Request $request)
    {
        $items = $request->input('items');
        if (!empty($items)) {
            // check validation
            $time_range = new TimeRange;
            $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::MENU_ITEM);
            if (($err_msg = $this->isValidTimeRangeForMenuItem(MenuItem::find($items[0]), $time_range))) {
                return back()->with('status_bad', $err_msg);
            }
            foreach ($items as $item_id) {
                $time_range = new TimeRange;
                $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::MENU_ITEM);

                $time_range->menu_item_id = $item_id;
                $time_range->save();
            }
        } else {
            return back()->with('status_bad', 'No items selected');
        }
        return back()->with('status_good', 'All menu items updated to have the time range');
    }
}
