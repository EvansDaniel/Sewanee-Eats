<?php

namespace App\Http\Controllers\Admin;

use App\CustomClasses\Availability\TimeRangeType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\CustomTraits\HandlesTimeRanges;
use App\CustomTraits\IsAvailable;
use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\TimeRange;
use Illuminate\Http\Request;
use Session;

/**
 * TODO: the find function along with all other DB accesses here need to be bullet proofed
 * Class MenuItemController
 * @package App\Http\Controllers\Admin
 */
class MenuItemController extends Controller
{

    use HandlesTimeRanges;

    public function showMenu($id)
    {
        $restaurant = Restaurant::find($id);
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        $on_demand_seller_type = RestaurantOrderCategory::ON_DEMAND;
        return view('admin.restaurants.showMenu',
            compact('restaurant', 'menu_items', 'on_demand_seller_type'));
    }

    public function showMenuItemCreateForm($r_id)
    {
        $categories = ItemCategory::orderBy('name', 'ASC')->get();
        $restaurant = Restaurant::find($r_id);
        return view('admin.restaurants.create_menu_item', compact('restaurant', 'categories'));
    }

    public function showMenuItemUpdateForm($r_id, $id)
    {
        $menu_item = MenuItem::find($id);
        $categories = ItemCategory::orderBy('name', 'ASC')->get();
        $available_times = json_decode($menu_item->available_times);
        $restaurant = Restaurant::find($r_id);
        return view('admin.restaurants.update_menu_item',
            compact('menu_item', 'categories', 'available_times',
                'restaurant'));
    }

    public function updateMenuItem(Request $request)
    {
        $menu_item = MenuItem::find($request->input('menu_item_id'));
        $menu_item = $this->createOrUpdateHelper($request, $menu_item);
        $menu_item->save();
        $r_id = $request->input('restaurant_id');
        return redirect()->route('adminShowMenu', ['id' => $r_id])
            ->with('status_good', 'Menu item updated');
    }

    public function createOrUpdateHelper($request, $menu_item)
    {
        $name = $request->input('name');
        $desc = $request->input('description');
        $price = $request->input('price');
        $r_id = $request->input('restaurant_id');
        $menu_item = $this->saveCategoryId($menu_item, $request);

        $menu_item->name = $name;
        $menu_item->price = $price;
        $menu_item->description = $desc;
        // can't update restaurant_id on update so it will be null then
        // restaurant_id only shows up in a hidden form input
        $menu_item->restaurant_id = $r_id;
        return $menu_item;
    }

    /**
     * @param $menu_item MenuItem A MenuItem instance that needs it's
     *        item_category_id attribute set during form request processing
     * @param $request Request The form request, either update or create
     * @return MenuItem returns $menu_item with the item_category_id
     *         attribute correctly set
     */
    private function saveCategoryId($menu_item, $request)
    {
        $create_cat_name = $request->input('create_category');
        $cat_id = $request->input('category_id');
        // determine they tried to create a new category or selected one
        if (empty($create_cat_name)) { // admin selected category
            $menu_item->item_category_id = $cat_id;
        } else { // admin created category
            $category = ItemCategory::where('name', $create_cat_name)->first();
            if ($category == null) {
                // this category doesn't already exist, so create it
                $category = new ItemCategory;
                $category->name = $create_cat_name;
                $category->save();
            }
            $menu_item->item_category_id = $category->id;
        }
        return $menu_item;
    }

    public function createMenuItem(Request $request)
    {
        $menu_item = new MenuItem;
        $menu_item = $this->createOrUpdateHelper($request, $menu_item);
        $menu_item->save();
        $r_id = $request->input('restaurant_id');
        $rest = Restaurant::find($r_id);
        if ($rest->isSellerType(RestaurantOrderCategory::ON_DEMAND)) {
            // redirect user to add available times for menu item
            $day_of_week_names = $this->getDayOfWeekNames();
            Session::flash('status_good', 'Menu item created. Now add the availability times of the menu item');
            return redirect()->route('showMenuItemAddAvailability',
                ['menu_item_id' => $menu_item->id]);
        }
        return redirect()->route('adminShowMenu', ['id' => $r_id])
            ->with('status_good', 'Menu item created');
    }

    public function deleteMenuItem($id)
    {
        MenuItem::find($id)->delete();
        return back()->with('status_good', 'Menu item deleted');
    }

    public function showMenuItemAvailability($menu_item_id)
    {
        $menu_item = MenuItem::find($menu_item_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.restaurants.menu_item_availability',
            compact('menu_item', 'day_of_week_names'));
    }

    public function showMenuItemAddAvailability($menu_item_id)
    {
        $menu_item = MenuItem::find($menu_item_id);
        $rest = $menu_item->restaurant;
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.restaurants.add_menu_item_availability',
            compact('menu_item', 'day_of_week_names', 'rest'));
    }

    public function showMenuItemUpdateAvailability($menu_item_id, $time_range_id)
    {
        $menu_item = MenuItem::find($menu_item_id);
        $time_range = TimeRange::find($time_range_id);
        $day_of_week_names = $this->getDayOfWeekNames();
        return view('admin.restaurants.update_menu_item_availability',
            compact('menu_item', 'time_range', 'day_of_week_names'));
    }

    public function menuItemUpdateAvailability(Request $request)
    {
        $time_range_id = $request->input('time_range_id');
        $menu_item_id = $request->input('menu_item_id');
        $menu_item = MenuItem::find($menu_item_id);
        $time_range = TimeRange::find($time_range_id);
        $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::MENU_ITEM);
        // go back if invalid
        if (!empty($msg = $this->isValidTimeRangeForMenuItem($menu_item, $time_range))) {
            return back()->with('status_bad', $msg)->withInput();
        }
        $time_range->save();
        // back to restaurant menu
        Session::flash('status_good', $menu_item->name . ' availability updated!');
        return redirect()->route('adminShowMenu',
            ['id' => $menu_item->restaurant->id]);
    }

    public function menuItemDeleteAvailability(Request $request)
    {
        $time_range_id = $request->input('time_range_id');
        $menu_item_id = $request->input('menu_item_id');
        $menu_item = MenuItem::find($menu_item_id);
        $time_range = TimeRange::find($time_range_id);
        $time_range->delete();
        return back()->with('status_good',
            'Availability time range for ' . $menu_item->name . ' deleted');
    }

    public function menuItemCreateAvailability(Request $request)
    {
        $menu_item_id = $request->input('menu_item_id');
        $time_range = new TimeRange;
        $time_range = $this->timeRangeSetup($time_range, $request, TimeRangeType::MENU_ITEM);
        $menu_item = MenuItem::find($menu_item_id);
        $time_range->menu_item_id = $menu_item->id;
        if (!empty($msg = $this->isValidTimeRangeForMenuItem($menu_item, $time_range))) {
            return back()->with('status_bad', $msg)->withInput();
        }
        $time_range->save();
        return back()->with('status_good',
            'Availability time range for ' . $menu_item->name . ' created.');
    }
}
