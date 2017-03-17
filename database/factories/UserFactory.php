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

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $available_times = json_encode([
        [
            '10:00-12:00',
            '20:00-22:00',
            ''
        ],
        [
            '08:00-10:00',
            '22:00-00:00',
            ''
        ],
        [
            '16:00-18:00',
            '',
            ''
        ],
        [
            '18:00-20:00',
            '16:00-18:00',
            ''
        ],
        [
            '12:00-14:00',
            '00:00-02:00',
            ''
        ],
        [
            '18:00-20:00',
            '20:00-22:00',
            '22:00-00:00'
        ],
        [
            '14:00-16:00',
            '16:00-18:00',
            '18:00-20:00'
        ]
    ]);

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: bcrypt('secret'),
        'available_times' => $available_times,
        'remember_token' => str_random(10),
    ];
});



