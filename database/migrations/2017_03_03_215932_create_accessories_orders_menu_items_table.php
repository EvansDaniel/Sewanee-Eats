<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessoriesOrdersMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // relates menu items for a certain order to accessories
        Schema::create('accessories_menu_items_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_items_orders_id')->unsigned();
            $table->integer('accessory_id')->unsigned();

            $table->foreign('accessory_id')
                ->references('id')->on('accessories')
                ->onDelete('cascade');
            $table->foreign('menu_items_orders_id')
                ->references('id')->on('menu_items_orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accessories_menu_items_orders');
    }
}
