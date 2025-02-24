<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flow_data', function (Blueprint $table) {
            $table->increments('q_cfd_id');
            $table->char('q_cfd_clnt_id', 64);
            $table->integer('q_cfd_cfl_id')->nullable();
            $table->tinyInteger('q_cfd_isNeedsForClient')->comment('0 - Need , 1 - Want');
            $table->double('q_cfd_cfda_clientAmt', 15, 2)->nullable();
            $table->tinyInteger('q_cfd_isNeedsForSpouse')->comment('0 - Need , 1 - Want');
            $table->double('q_cfd_cfda_spouseAmt', 15, 2)->nullable();
            $table->double('q_cfd_cfda_clientAmtExpense', 15, 2)->nullable();
            $table->double('q_cfd_cfda_spouseAmtExpense', 15, 2)->nullable();
            $table->double('q_cfd_cfdb_clientAmt', 15, 2)->nullable();
            $table->double('q_cfd_cfdb_spouseAmt', 15, 2)->nullable();
            $table->integer('q_cfd_targetRetireAmtInPercent')->nullable();
            $table->date('q_cfd_dateUpdated');
            $table->date('q_cfd_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flow_data');
    }
}
