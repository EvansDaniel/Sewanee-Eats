<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerExtraEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_extra_earnings', function (Blueprint $table) {
            $table->increments('id');
            $table->double('hours_worked');
            $table->string('description_of_hours_worked');
            $table->double('pay_per_hour');

            $table->timestamp('date_of_work')->default(Carbon::now());

            $table->integer('worker_id')->unsigned();
            $table->foreign('worker_id')
                ->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**a
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worker_extra_earnings');
    }
}
