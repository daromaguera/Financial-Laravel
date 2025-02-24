<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flow_analysis', function (Blueprint $table) {
            $table->increments('q_cfa_id');
            $table->char('q_cfa_clnt_id', 64);
            $table->double('q_cfa_targetCashInF_client', 15, 2)->nullable();
            $table->double('q_cfa_targetCashInF_spouse', 15, 2)->nullable();
            $table->double('q_cfa_targetCashOutF_client', 15, 2)->nullable();
            $table->double('q_cfa_targetCashOutF_spouse', 15, 2)->nullable();
            $table->integer('q_cfa_clientShareRFN')->nullable();
            $table->integer('q_cfa_spouseShareRFN')->nullable();
            $table->text('q_cfa_expectedSavings')->nullable();
            $table->text('q_cfa_goesWell')->nullable();
            $table->integer('q_cfa_reduceCFAttempt')->nullable()->comment('1 - Yes, 0 - No');
            $table->date('q_cfa_dateUpdated');
            $table->date('q_cfa_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flow_analysis');
    }
}
