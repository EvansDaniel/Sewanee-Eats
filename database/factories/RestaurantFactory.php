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

use App\Models\Restaurant;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Restaurant::class, function (Faker\Generator $faker) {
    // There is no index in the referenced table where the referenced columns appear as the first columns.
    $locations = [
        'campus',
        'downtown',
        'monteagle'
    ];
    $hours_open = [
        [
            '13-17',
            '20-24',
            ''
        ],
        [
            '8-12',
            '20-24',
            ''
        ],
        [
            '13-17',
            '',
            ''
        ],
        [
            '20-0',
            '',
            ''
        ],
        [
            '9-17',
            '20-0',
            ''
        ],
        [
            '8-16',
            '',
            ''
        ],
        [
            '8-12',
            '',
            ''
        ]
    ];
    // note that hours_open is a 24 hour clock
    // so this one is open from 1 to 5 and 8pm to 12am on Mondays
    return [
        'name' => 'My restaurant name',
        'hours_open' => json_encode($hours_open),
        'description' => 'My restaurant description',
        'location' => $locations[RAND(0,2)],
        'image_url' => 'http://www.minervas.net/images/jqg_13539536932.jpg'
    ];
});