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
        $accessories = Accessory::all();
        $rand_indexs = [[0, 3], [4, 7], [8, 9]];
        foreach ($menu_items as $menu_item) {
            for ($i = 0; $i < 3; $i++) {
                $rand = mt_rand($rand_indexs[$i][0], $rand_indexs[$i][1]);
                DB::table('menu_items_accessories')->insert([
                    'menu_item_id' => $menu_item->id,
                    'accessory_id' => $accessories[$rand]->id
                ]);
            }
        }
    }
}
