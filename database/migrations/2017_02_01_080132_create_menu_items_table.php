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
            $table->longText('available_times');
            $table->integer('restaurant_id')->unsigned();
            $table->integer('item_category_id')->unsigned();
            // Meaningful values:
            // TODO:
            // 1 breakfast, 2 lunch, 3 dinner
            // 4 breakfast and lunch
            // 5 breakfast and dinner
            // 6 lunch and dinner
            // 7 breakfast,lunch, and dinner
            // $table->integer('available_during_hours');
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
