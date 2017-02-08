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
            // subtotal can be computed from the below two fields
            $table->float('total_price')->unsigned();
            $table->float('profit')->unsigned();
            $table->float('state_tax')->unsigned();

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
