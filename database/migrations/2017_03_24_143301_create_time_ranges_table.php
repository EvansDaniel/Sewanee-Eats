<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start_dow'); // Carbon->format('l');
            $table->integer('start_hour'); // mililtary hours
            $table->integer('start_min');
            $table->string('end_dow');
            $table->integer('end_hour');
            $table->integer('end_min');
            //$table->integer('user_id')->unsigned()->nullable();
            $table->integer('restaurant_id')->unsigned()->nullable();
            $table->integer('menu_item_id')->unsigned()->nullable();
            $table->integer('time_range_type'); // shift, weekly special, on demand, etc.
            // todo: important
            // user time ranges have to be deleted b/c shifts change by week,
            // so when we are creating user time schedules, we need to provide the time at which the
            // time range should be deleted such as 3 hours after the shift

            // user id that represents an employee of Sewanee Eats that has a shift at specified time
            /*$table->foreign('user_id')
                ->references('id')->on('users');*/
            $table->foreign('restaurant_id')
                ->references('id')->on('restaurants');
            $table->foreign('menu_item_id')
                ->references('id')->on('menu_items');
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
        Schema::dropIfExists('time_ranges');
    }
}
