<?php

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Restaurant::class,10)->create();
    }
}
