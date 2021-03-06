<?php

namespace App\Http\Controllers\Api;

use App\CustomClasses\ShoppingCart\ItemType;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

/**
 * Class ItemController JS endpoint on showMenu page for menu.js
 * @package App\Http\Controllers\Api
 */

class ItemController extends Controller
{
    /**
     * @param $cart_item_id integer id of the menu item whose
     * accessories you wish to retrieve
     * @return string returns a json string of the
     * accessories of the menu item with id = $id
     * arranged in two arrays: accessories that cost money
     * and free accessories
     */
    public function accessories(Request $request)
    {
        $item_id = $request->query("item_id");
        $item_type = $request->query("item_type");
        if ($item_type == ItemType::RESTAURANT_ITEM) { // only restaurant items have accessories right now
            $menu_item = MenuItem::find($item_id);
            $pricy = [];
            $free = [];
            if (!empty($menu_item)) {
                foreach ($menu_item->accessories as $accessory) {
                    if ($accessory->price == 0) { // free
                        $free[] = $accessory;
                    } else { // pricy
                        $pricy[] = $accessory;
                    }
                }
            }
            $accessories = ['accs' => ['free' => $free, 'pricy' => $pricy]];
            return json_encode($accessories);
        }
        return null;
    }
}
