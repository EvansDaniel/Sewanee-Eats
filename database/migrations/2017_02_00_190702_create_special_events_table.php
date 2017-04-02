<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // used for our specials and for third party seller events
        Schema::create('special_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_name');
            $table->string('host_name');
            $table->string('host_logo');
            $table->string('event_description');
            // when ordering starts and ends
            $table->boolean('for_profit');

            /*$table->integer('time_range_id')->unsigned();
            $table->foreign('time_range_id')->
                references('id')->on('time_ranges');*/
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
        Schema::dropIfExists('special_events');
    }
}
