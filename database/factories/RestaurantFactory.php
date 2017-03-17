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

use App\CustomClasses\ShoppingCart\SellerType;
use App\Models\Restaurant;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Restaurant::class, function (Faker\Generator $faker) {
    // There is no index in the referenced table where the referenced columns appear as the first columns.
    $locations = [
        'campus',
        'downtown',
        'monteagle'
    ];
    // format of available_times
    /*[
        'monday','tuesday','....','sunday'
    ]*/
    $available_times = [
        [
            '13:30-17:30',
            '20:30-24:30',
            ''
        ],
        [
            '8:30-12:30',
            '20:30-24:30',
            ''
        ],
        [
            '13:30-17:30',
            '',
            ''
        ],
        [
            '20:30-00:30',
            /*'01:00-05:00',*/
            '',
            ''
        ],
        [
            '9:30-17:30',
            '20:30-00:30',
            ''
        ],
        [
            '8:30-16:30',
            '',
            ''
        ],
        [
            '8:30-12:30',
            '',
            ''
        ]
    ];
    // note that hours_open is a 24 hour clock
    // so this one is open from 1 to 5 and 8pm to 12am on Mondays
    $images = ['http://localhost:8000/app/restaurants/n1s35o1.gif'];
    return [
        'name' => $faker->company,
        'available_times' => json_encode($available_times),
        'location' => $locations[RAND(0,2)],
        'seller_type' => SellerType::WEEKLY_SPECIAL,
        'image_url' => $images[mt_rand(0, count($images) - 1)]
    ];
});