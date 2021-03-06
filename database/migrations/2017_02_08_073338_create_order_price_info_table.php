<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPriceInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_price_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->float('total_price')->unsigned();
            $table->float('subtotal')->unsigned();
            $table->float('profit');
            $table->double('cost_of_food');
            $table->float('stripe_fees');
            $table->float('delivery_fee');
            // used to be state_tax_charged
            $table->float('tax_charged')->unsigned();
            $table->float('tax_percentage'); // +
            $table->foreign('order_id')
                ->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_price_info');
    }
}
