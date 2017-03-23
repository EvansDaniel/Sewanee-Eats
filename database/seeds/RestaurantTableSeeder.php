<?php

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Restaurant::class, 5)->create(['seller_type' => RestaurantOrderCategory::WEEKLY_SPECIAL]);
        factory(Restaurant::class, 5)->create(['seller_type' => RestaurantOrderCategory::ON_DEMAND]);
    }
}
