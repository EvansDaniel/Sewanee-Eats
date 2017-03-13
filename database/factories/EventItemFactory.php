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

use App\Models\EventItem;
use App\Models\SpecialEvent;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EventItem::class, function (Faker\Generator $faker) {
    $event = SpecialEvent::all();
    return [
        'name' => 'Apple Pie',
        'price' => '1',
        'description' => 'description',
        'event_id' => $event[0]->id
    ];
});
