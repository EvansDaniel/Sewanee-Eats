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

use App\Models\SpecialEvent;
use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(SpecialEvent::class, function (Faker\Generator $faker) {

    return [
        'event_name' => 'Alpha Delta PI PI Day',
        'host_name' => 'Alpha Delta PI',
        'event_description' => 'Blah blah description',
        'start_time' => Carbon::now(),
        'end_time' => Carbon::now(),
        'for_profit' => 0
    ];
});
