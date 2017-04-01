<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouriersOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couriers_orders', function (Blueprint $table) {
            $table->integer('courier_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('courier_payment');


            $table->foreign('courier_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            // going to leave these timestamps so there is data about
            // when a user added him/herself as a employee to the order request
            $table->timestamps();
            $table->softDeletes();
            // TODO: use soft deletes to keep all records of couriers being assigned to orders
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('couriers_orders');
    }
}
