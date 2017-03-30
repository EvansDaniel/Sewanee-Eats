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

use App\CustomClasses\Courier\CourierTypes;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Models\Order;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Order::class, function (Faker\Generator $faker) {
    return [
        'is_open_order' => 0,
        'order_types' => json_encode(['on_demand' => RestaurantOrderCategory::ON_DEMAND]),
        'is_delivered' => 0,
        'is_being_processed' => 0,
        'courier_types' => json_encode([CourierTypes::DRIVER]), // drivers
        'payment_type' => PaymentType::STRIPE_PAYMENT,
        'phone_number' => '93   16913594',
        'c_name' => 'Mark Garcia',
        'venmo_username' => null, // stripe payment
        'delivery_location' => '249 Circle Dr, Cowan, TN 37318',
        'email_of_customer' => 'evansdb0@sewanee.edu',
        'is_cancelled' => 0,
        'was_refunded' => 0
    ];
});
