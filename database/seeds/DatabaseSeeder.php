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
            $this->call(RoleTableSeeder::class);
            $this->call(UserTableSeeder::class);
            $this->call(RolesUsersTableSeeder::class);
        if (env('APP_ENV') === "local") {
            // after role
            // after role and user

            $this->call(RestaurantTableSeeder::class);
            $this->call(ItemCategoryTableSeeder::class);
            // after item category
            $this->call(MenuItemTableSeeder::class);
            $this->call(AccessoryTableSeeder::class);
            // after menu_items and accessories
            //$this->call(MenuItemAccessoriesTableSeeder::class);
            //$this->call(OrdersTableSeeder::class);

            // Must go after restaurants, users, menu_items
            //$this->call(MenuItemsOrdersTableSeeder::class);
            // Not necessarily needed b/c we can access the restaurant through the menu item
            //$this->call(CouriersOrdersTableSeeder::class);
            $this->call(ArticleTableSeeder::class);
            $this->call(SpecialEventsTableSeeder::class);
            $this->call(EventItemTableSeeder::class);
            //$this->call(TimeRangesSeeder::class);
        }

        Eloquent::reguard();

    }
}