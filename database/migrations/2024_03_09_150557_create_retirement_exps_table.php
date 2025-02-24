<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirementExpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirement_exps', function (Blueprint $table) {
            $table->increments('q_retExp_id');
            $table->char('q_retExp_clientID', 64);
            $table->integer('q_retExp_retExpList_id')->comment('Retirement Expenses List');
            $table->double('q_retExp_presentValAmtCL', 15, 2);
            $table->double('q_retExp_presentValAmtSP', 15, 2);
            $table->date('q_retExp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retirement_exps');
    }
}
