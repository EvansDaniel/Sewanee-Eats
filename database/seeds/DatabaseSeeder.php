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
        Eloquent::unguard();
        if (env('APP_ENV') === "local") {
            $this->call(RoleTableSeeder::class);
            // after role
            $this->call(UserTableSeeder::class);
            // after role and user
            $this->call(RolesUsersTableSeeder::class);

            $this->call(RestaurantTableSeeder::class);
            $this->call(ItemCategoryTableSeeder::class);
            // after item category
            $this->call(MenuItemTableSeeder::class);
            $this->call(AccessoryTableSeeder::class);
            // after menu_items and accessories
            $this->call(MenuItemAccessoriesTableSeeder::class);
            //$this->call(OrdersTableSeeder::class);

            // Must go after restaurants, users, menu_items
            $this->call(MenuItemsOrdersTableSeeder::class);
            // Not necessarily needed b/c we can access the restaurant through the menu item
            //$this->call(RestaurantsOrdersTableSeeder::class);
            //$this->call(CouriersOrdersTableSeeder::class);
        }

        Eloquent::reguard();

    }
}
