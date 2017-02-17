<?php

use App\Models\Accessory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemAccessoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_items = MenuItem::all();
        foreach ($menu_items as $menu_item) {
            for ($i = 0; $i < 3; $i++) {
                $accessory = Accessory::orderByRaw("RAND()")->first();
                DB::table('menu_items_accessories')->insert([
                    'menu_item_id' => $menu_item->id,
                    'accessory_id' => $accessory->id
                ]);
            }
        }
    }
}
