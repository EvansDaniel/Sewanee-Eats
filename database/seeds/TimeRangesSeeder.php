<?php

use App\CustomClasses\Availability\TimeRangeType;
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
                /*factory(TimeRange::class, 1)->create(
                    [
                        'time_range_type' => TimeRangeType::SHIFT,
                        'start_hour' => mt_rand(0,12),
                        'end_hour' => mt_rand(13,23)
                    ])
                    ->each(function ($t) use ($user) {
                        //$t->users()->attach([$user->id => ['courier_type' => CourierTypes::DRIVER]]);
                    });*/
            }
        }
        $restaurants = Restaurant::all();
        if (!empty($restaurants)) {
            foreach ($restaurants as $restaurant) {
                /*factory(TimeRange::class, 1)->create([
                    'restaurant_id' => $restaurant->id,
                    'time_range_type' => $restaurant->getSellerType() == RestaurantOrderCategory::ON_DEMAND ?
                        TimeRangeType::ON_DEMAND : TimeRangeType::WEEKLY_SPECIAL
                ]);*/
            }
        }
        $menu_items = MenuItem::all();
        if (!empty($menu_items)) {
            foreach ($menu_items as $menu_item) {
                factory(TimeRange::class, 1)->create([
                    'menu_item_id' => $menu_item->id,
                    'time_range_type' => TimeRangeType::MENU_ITEM
                ]);
            }
        }
    }
}
