<?php

use App\Models\Article;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Article::class, function (Faker\Generator $faker) {

    return [
        'title' => 'SewaneeEats Does It Again',
        'image_url' => 'https://www.moooi.com/sites/default/files/styles/large/public/product-images/random_detail.jpg?itok=ErJveZTY',
        'body' => $faker->paragraph,
        'subtitle' => 'SewaneeEats Expands to Centre College'
    ];
});