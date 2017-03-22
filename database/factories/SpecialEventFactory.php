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

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\SpecialEvent;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(SpecialEvent::class, function (Faker\Generator $faker) {

    return [
        'event_name' => 'Alpha Delta PI PI Day',
        'host_name' => 'Alpha Delta PI',
        'event_description' => 'Blah blah description',
        'host_logo' => 'https://pbs.twimg.com/profile_images/614120821414428672/qUDRd9gk.jpg',
        'seller_type' => RestaurantOrderCategory::EVENT,
        'for_profit' => 0
    ];
});
