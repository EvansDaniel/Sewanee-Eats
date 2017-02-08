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
        for ($i = 0; $i < 30; $i++) {
            $menu_item = MenuItem::orderByRaw("RAND()")->first();
            $accessory = Accessory::orderByRaw("RAND()")->first();
            DB::table('menu_items_accessories')->insert([
                'menu_item_id' => $menu_item->id,
                'accessory_id' => $accessory->id
            ]);
        }
    }
}
