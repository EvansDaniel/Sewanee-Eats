<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            // has been paid for
            $table->boolean('is_paid_for');
            // can be address or location description
            // will we provide locations via select or will it be via text box?
            $table->string('delivery_location', 150)->nullable();
            // used to be boolean type -> is_weekly_special
            // this might be multiple order types, stored as json -> these are RestaurantOrderCategory(s)
            $table->string('order_types');
            $table->string('phone_number')->nullable();
            $table->string('email_of_customer');
            $table->string('c_name');
            // cancelled may not mean refunded
            $table->boolean('is_cancelled');
            // was this order refunded?
            $table->boolean('was_refunded');
            $table->boolean('is_delivered');
            $table->boolean('is_being_processed');
            $table->string('courier_types')->nullable(); // json string denoting the types of couriers that can deliver this order
            // did they opt to pay with venmo
            // was paid_with_venmo of type boolean
            $table->integer('payment_type');
            $table->string('venmo_username')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
