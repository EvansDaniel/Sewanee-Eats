<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // has one category
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->float('price');
            $table->longText('available_times')->nullable();
            $table->integer('restaurant_id')->unsigned();
            $table->integer('item_category_id')->unsigned();
            $table->foreign('restaurant_id')
                ->references('id')->on('restaurants')
                ->onDelete('cascade');
            $table->foreign('item_category_id')
                ->references('id')->on('item_categories');
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
        Schema::dropIfExists('menu_items');
    }
}
