<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_info', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_delivering_order'); // whether this courier has a shift right now or not
            $table->string('phone_number'); // useful for manager
            $table->integer('current_order_id')->nullable();
            $table->integer('user_id')->unsigned(); // user id of the courier
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
        Schema::dropIfExists('courier_info');
    }
}
