<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title'); // title of the special
            $table->string('description'); // what's up with it
            $table->string('delivery time');
            $table->string('delivery_location');
            $table->integer('time_range_id')->unsigned();
            $table->foreign('time_range_id')// is avaiable on site
                ->references('id')->on('time_ranges');
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
        Schema::dropIfExists('specials');
    }
}
