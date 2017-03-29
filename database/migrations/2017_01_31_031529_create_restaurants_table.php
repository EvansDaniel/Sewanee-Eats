<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Has many MenuItems
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            // used to be location, this is the exact address
            $table->string('address')->nullable();
            $table->integer('seller_type'); // +
            // using this field we can switch on and off the availability
            // of weekly special or on_demand restaurants to users
            $table->integer('is_available_to_customers');
            $table->boolean('callable')->nullable();  // +
            $table->string('phone_number')->nullable();
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
