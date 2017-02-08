<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function showMenu($id)
    {
        $restaurant = Restaurant::find($id);
        foreach ($restaurant->menuItems as $item) {
            $menu_items[$item->itemCategory->name][] = $item;
        }
        return view('admin.showMenu', compact('restaurant', 'menu_items'));
    }

    public function showMenuItemCreateForm()
    {
        $restaurants = Restaurant::orderBy('name', 'ASC')->get();
        $categories = ItemCategory::orderBy('name', 'ASC')->get();
        return view('admin.create_menu_item', compact('restaurants', 'categories'));
    }

    public function showMenuItemUpdateForm($id)
    {
        $menu_item = MenuItem::find($id);
        $restaurants = Restaurant::orderBy('name', 'ASC')->get();
        $categories = ItemCategory::orderBy('name', 'ASC')->get();
        return view('admin.update_menu_item',
            compact('menu_item', 'restaurants', 'categories'));
    }

    public function updateMenuItem(Request $request)
    {
        $menu_item = MenuItem::find($request->input('menu_item_id'));
        $this->createOrUpdateHelper($request, $menu_item);
        return back()->with('status_good', 'Menu item updated');
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
        $menu_item->restaurant_id = $r_id;
        $menu_item->save();
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
        $this->createOrUpdateHelper($request, $menu_item);
        return back()->with('status_good', 'Menu item created');
    }

    public function deleteMenuItem($id)
    {
        MenuItem::find($id)->delete();
        return back()->with('status_good', 'Menu item deleted');
    }
}
