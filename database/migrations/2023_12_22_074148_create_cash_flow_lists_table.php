<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flow_lists', function (Blueprint $table) {
            $table->increments('q_cfl_id');
            $table->longText('q_cfl_descripx');
            $table->integer('q_cfl_type')->unsigned()->comment('0 - inflow, 1 - outflow');
            $table->integer('q_cfl_isOther')->unsigned()->comment('1 - Yes, No - 0');
            $table->integer('q_cfl_order');
            $table->date('q_cfl_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flow_lists');
    }
}
