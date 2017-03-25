<?php

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\TimeRange;
use Illuminate\Database\Seeder;

class TimeRangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::ofType('courier')->first();
        if (!empty($role)) {
            foreach ($role->users as $user) {
                factory(TimeRange::class, 1)->create(['user_id' => $user->id]);
            }
        }
        $restaurants = Restaurant::all();
        if (!empty($restaurants)) {
            foreach ($restaurants as $restaurant) {
                factory(TimeRange::class, 1)->create(['restaurant_id' => $restaurant->id]);
            }
        }
        $menu_items = MenuItem::all();
        if (!empty($menu_items)) {
            foreach ($menu_items as $menu_item) {
                factory(TimeRange::class, 1)->create(['menu_item_id' => $menu_item->id]);
            }
        }
    }
}
