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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ItemCategory::class, function (Faker\Generator $faker) {

    $categories = [
        'Pizza',
        'Sandwiches',
        'Coffee'
    ];

    return [
        'name' => $categories[mt_rand(0,2)]
    ];
});
