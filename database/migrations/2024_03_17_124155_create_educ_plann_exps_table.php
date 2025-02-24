<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducPlannExpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educ_plann_exps', function (Blueprint $table) {
            $table->increments('q_educPExp_id');
            $table->integer('q_educPExp_famComp_id');
            $table->integer('q_educPExp_educPExpList_id')->comment('Education Planning Expenses Default List');
            $table->double('q_educPExp_presentValAmt', 15, 2);
            $table->integer('q_educPExp_avgInflationRate')->nullable();
            $table->double('q_educPExp_futureNeededValAmt', 15, 2);
            $table->date('q_educPExp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educ_plann_exps');
    }
}
