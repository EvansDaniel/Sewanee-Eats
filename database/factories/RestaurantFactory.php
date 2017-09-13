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

use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Restaurant;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Restaurant::class, function (Faker\Generator $faker) {
    // There is no index in the referenced table where the referenced columns appear as the first columns.
    $locations = [
        '521 W Main St Monteagle, TN 37356', // mcdonalds
        '640 Dixie Lee Hwy Monteagle, TN 37356', // wendy's
        '12595 Sollace M Freeman Hwy, Sewanee, TN 37375' // shenanigans
    ];
    $rest_names = [
        'Mcdonald\'s',
        'Wendy\'s',
        'Shenanigans'
    ];

    $images = ['http://static.asiawebdirect.com/m/bangkok/portals/pattaya-bangkok-com/homepage/best-restaurants/allParagraphs/0/top10Set/02/image/radius-restaurant-1200.jpg'];
    $rest_loc_index = mt_rand(0, 2);
    return [
        'name' => $rest_names[$rest_loc_index],
        'address' => $locations[$rest_loc_index],
        'seller_type' => RestaurantOrderCategory::ON_DEMAND,
        'is_available_to_customers' => 1,
        'delivery_payment_for_courier' => 4, // only for on demand
        'callable' => true,
        'phone_number' => '5555555555',
        'image_url' => $images[0],
    ];

});