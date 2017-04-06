<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\CustomClasses\Availability\TimeRangeType;
use App\Models\TimeRange;

$factory->define(TimeRange::class, function (Faker\Generator $faker) {
    return [
        'start_dow' => 'Saturday',
        'start_hour' => 12,
        'start_min' => 00,
        'end_dow' => 'Saturday',
        'end_hour' => 23,
        'end_min' => 59,
        'time_range_type' => TimeRangeType::SHIFT
    ];
});
