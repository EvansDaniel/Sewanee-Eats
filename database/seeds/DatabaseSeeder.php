<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RestaurantTableSeeder::class);
        $this->call(ItemCategoryTableSeeder::class);
        $this->call(MenuItemTableSeeder::class);

    }
}
