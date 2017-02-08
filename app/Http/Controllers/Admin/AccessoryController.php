<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    public function showAccessories($id)
    {
        $menu_item = MenuItem::find($id);
        // $accessories = $menu_item->accessories->sortBy('name');
        $accessories = $menu_item->accessories->sort(function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        return view('admin.accessory.show_accessories',
            compact('accessories', 'menu_item'));
    }

    public function showCreateAccessoryForm($id)
    {
        $menu_item = MenuItem::find($id);
        return view('admin.accessory.create_accessory_form',
            compact('menu_item'));
    }

    // Note that the order of the parameters must match the
    // corresponding route parameters in web.php
    public function showUpdateAccessoryForm($m_id, $a_id)
    {
        $accessory = Accessory::find($a_id);
        $menu_item = MenuItem::find($m_id);
        return view('admin.accessory.update_accessory_form',
            compact('accessory', 'menu_item'));
    }

    // GOOD
    public function createAccessory(Request $request)
    {
        $menu_item_id = $request->input('menu_item_id');
        $acc = new Accessory;
        $acc = $this->createAndUpdateHelper($request, $acc);
        $acc->menuItems()->attach($menu_item_id);
        return redirect(route('showAccessories', ['id' => $menu_item_id]))
            ->with('status_good', "The accessory was created successfully!");
    }

    private function createAndUpdateHelper(Request $request, $acc)
    {
        $name = $request->input('name');
        $price = $request->input('price');
        $acc->name = $name;
        $acc->price = $price;
        $acc->save();
        return $acc;
    }

    public function updateAccessory(Request $request)
    {
        $menu_item_id = $request->input('menu_item_id');
        $acc = Accessory::find($request->input('accessory_id'));
        $this->createAndUpdateHelper($request, $acc);
        return redirect(route('showAccessories', ['id' => $menu_item_id]))
            ->with('status_good', "The accessory was updated!");
    }

    public function deleteAccessory(Request $request)
    {
        $menu_item_id = $request->input('menu_item_id');
        $acc_id = $request->input('accessory_id');
        Accessory::find($acc_id)->menuItems()->detach($menu_item_id);
        return redirect(route('showAccessories', ['id' => $menu_item_id]))
            ->with('status_good', "The accessory was deleted!");
    }
}
