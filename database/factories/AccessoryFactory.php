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

use App\Models\Accessory;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Accessory::class, function (Faker\Generator $faker) {

    return [
        'name' => 'My Accessory',
        'price' => 2.99
    ];
});
