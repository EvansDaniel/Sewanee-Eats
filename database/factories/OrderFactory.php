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

use App\Models\Order;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Order::class, function (Faker\Generator $faker) {
    $bool = [0, 1];
    $location = ['Smith Hall', 'Tuckaway', 'Library', 'Quintard'];
    $email = "hello@gmail.com";
    return [
        'is_open_order' => 1,
        'is_weekly_special' => 0,
        'is_delivered' => 0,
        'delivery_location' => $location[mt_rand(0, 3)],
        'email_of_customer' => $email,
        'is_cancelled' => 0,
        //,'contact_number_of_user' => 1,
        'was_refunded' => $bool[mt_rand(0, 1)]
    ];
});
