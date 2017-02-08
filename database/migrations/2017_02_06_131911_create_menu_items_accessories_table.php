<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items_accessories', function (Blueprint $table) {
            $table->integer('menu_item_id')->unsigned();
            $table->integer('accessory_id')->unsigned();
            $table->foreign('menu_item_id')
                ->references('id')->on('menu_items')
                ->onDelete('cascade');
            $table->foreign('accessory_id')
                ->references('id')->on('accessories')
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
        Schema::dropIfExists('menu_items_accessories');
    }
}
