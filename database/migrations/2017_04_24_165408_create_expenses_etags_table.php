<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesEtagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_etags', function (Blueprint $table) {
            $table->increments('id');


            $table->integer('etag_id')->unsigned();
            $table->integer('expense_id')->unsigned();


            $table->foreign('etag_id')
                ->references('id')->on('etags');
            $table->foreign('expense_id')
                ->references('id')->on('expenses');

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
        Schema::dropIfExists('expenses_etags');
    }
}
