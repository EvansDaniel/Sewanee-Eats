<?php

namespace Tests\Unit\Availability;

use App\CustomClasses\Availability\IsAvailable;
use App\CustomClasses\Schedule\Shift;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use ItemCategoryTableSeeder;
use MenuItemTableSeeder;
use RestaurantTableSeeder;
use RolesUsersTableSeeder;
use RoleTableSeeder;
use Tests\TestCase;
use TimeRangesSeeder;
use UserTableSeeder;

class IsAvailableTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function itCorrectlyDeterminesAvailabilityForCouriers()
    {
        $this->populateRolesAndUsers();
        // check that this works for couriers
        $couriers = Role::ofType('courier')->first()->users;
        $isAvail = new IsAvailable($couriers[0]);
        \Log::info($isAvail->isAvailableNow(0));
    }

    private function populateRolesAndUsers()
    {
        \Eloquent::unguard();
        $user_seed = new UserTableSeeder();
        $user_seed->run();
        $role_seed = new RoleTableSeeder();
        $role_seed->run();
        $role_users_seed = new RolesUsersTableSeeder();
        $role_users_seed->run();
        $time_range_seed = new TimeRangesSeeder();
        $time_range_seed->run();
        \Eloquent::reguard();
    }

    /**
     * @test
     */
    public function itCorrectlyDeterminesAvailabilityForRestaurants()
    {
        // there functions run timerangeseeder twice
        $this->populateRolesAndUsers();
        $this->populateRestaurants();
        $r = Restaurant::all()->first();
        $shift = new Shift();
        \Log::info(count($shift->getCurrentShifts()));
    }

    private function populateRestaurants()
    {
        \Eloquent::unguard();
        $restaurant_seed = new RestaurantTableSeeder();
        $restaurant_seed->run();
        $time_range_seed = new TimeRangesSeeder();
        $time_range_seed->run();
        \Eloquent::reguard();
    }

    /**
     * @test
     */
    public function itCorrectlyDeterminesAvailabilityForMenuItems()
    {
        $this->populateMenuItems();
        $menu_item = MenuItem::all()->first();
        $isAvail = new IsAvailable($menu_item);
        \Log::info($isAvail->isAvailableNow(0));
    }

    private function populateMenuItems()
    {
        \Eloquent::unguard();
        $restaurant_seed = new RestaurantTableSeeder();
        $restaurant_seed->run();
        $item_category_seed = new ItemCategoryTableSeeder();
        $item_category_seed->run();
        $menu_item_seed = new MenuItemTableSeeder();
        $menu_item_seed->run();
        $time_range_seed = new TimeRangesSeeder();
        $time_range_seed->run();
        \Eloquent::reguard();
    }
}