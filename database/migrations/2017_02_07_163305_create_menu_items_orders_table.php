<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items_orders', function (Blueprint $table) {
            $table->integer('order_id')->unsigned();
            $table->integer('menu_item_id')->unsigned();

            $table->string('special_instructions');
            $table->integer('quantity')->unsigned();

            // either add create json array of accessories for this menu_item_order
            // or create a table joining a menu_item_order with
            // the accessories table in a many-to-many relationship

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('menu_item_id')
                ->references('id')->on('menu_items')
                ->onDelete('cascade');
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
        Schema::dropIfExists('menu_items_orders');
    }
}
