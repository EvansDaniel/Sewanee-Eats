<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;

class MenuItemInfoController extends Controller
{
    /**
     * @param $id integer id of the menu item whose
     * accessories you wish to retrieve
     * @return string returns a json string of the
     * accessories of the menu item with id = $id
     * arranged in two arrays: accessories that cost money
     * and free accessories
     */
    public function ajaxGetMenuItemAccessories($id)
    {
        $menu_item = MenuItem::find($id);
        $pricy = [];
        $free = [];
        foreach ($menu_item->accessories as $accessory) {
            if ($accessory->price == 0) { // free
                $free[] = $accessory;
            } else { // pricy
                $pricy[] = $accessory;
            }
        }
        $accessories = ['accs' => ['free' => $free, 'pricy' => $pricy]];
        return json_encode($accessories);
    }
}
