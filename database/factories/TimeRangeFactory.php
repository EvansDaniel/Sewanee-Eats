<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\TimeRange;

$factory->define(TimeRange::class, function (Faker\Generator $faker) {
    return [
        'start_dow' => 'Friday',
        'start_hour' => 9,
        'start_min' => 24,
        'end_dow' => 'Saturday',
        'end_hour' => 10,
        'end_min' => 27,
    ];
});
