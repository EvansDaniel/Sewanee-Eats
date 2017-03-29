<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRangesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_ranges_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('time_range_id')->unsigned();
            $table->integer('user_id')->unsigned();

            // managers don't have a courier type so it can be nullable
            $table->integer('courier_type')->nullable();
            $table->foreign('time_range_id')
                ->references('id')->on('time_ranges')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('time_ranges_users');
    }
}
