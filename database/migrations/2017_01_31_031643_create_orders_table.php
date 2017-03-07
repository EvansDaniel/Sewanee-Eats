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
            $table->boolean('is_open_order');
            $table->string('delivery_location', 150)->nullable();
            $table->boolean('is_weekly_special');
            //$table->bigInteger('customer_phone_number');
            $table->string('email_of_customer');
            $table->string('c_name');
            $table->boolean('is_cancelled');
            // was this order refunded?
            $table->boolean('was_refunded');
            // did they opt to pay with venmo
            $table->boolean('paid_with_venmo');
            $table->string('venmo_username')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
