<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Models\ItemCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(MenuItem::class, function (Faker\Generator $faker) {
    $restaurant = Restaurant::orderByRaw("RAND()")->first();
    $item_category = ItemCategory::orderByRaw("RAND()")->first();
    $decimals = [
        '.99',
        '.49',
        '.89'
    ];
    return [
        'name' => 'My menu item name',
        'description' => 'My menu item description',
        'price' => mt_rand(3,18) . $decimals[mt_rand(0,2)],
        'restaurant_id' => $restaurant->id,
        'item_category_id' => $item_category->id
    ];
});
