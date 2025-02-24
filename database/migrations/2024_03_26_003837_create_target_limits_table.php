<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_limits', function (Blueprint $table) {
            $table->increments('q_targLim_id');
            $table->char('q_targLim_clientID', 64);
            $table->integer('q_targLim_famCompID')->comment('ID from Family Composition');
            $table->integer('q_targLim_type')->comment('0 - Client, 1 - Partner (any), 2 - Spouse, 3 - Child, 4 - Father, 5 - Mother');
            $table->double('q_targLim_MBL_inPatient', 15, 2)->nullable();
            $table->double('q_targLim_ABL_inPatient', 15, 2)->nullable();
            $table->double('q_targLim_LBL_inPatient', 15, 2)->nullable();
            $table->double('q_targLim_MBL_outPatient', 15, 2)->nullable();
            $table->double('q_targLim_ABL_outPatient', 15, 2)->nullable();
            $table->double('q_targLim_LBL_outPatient', 15, 2)->nullable();
            $table->double('q_targLim_MBL_critIllness', 15, 2)->nullable();
            $table->double('q_targLim_ABL_critIllness', 15, 2)->nullable();
            $table->double('q_targLim_LBL_critIllness', 15, 2)->nullable();
            $table->double('q_targLim_labLimit', 15, 2)->nullable()->comment('Laboratory Limit');
            $table->double('q_targLim_hospIncome', 15, 2)->nullable()->comment('Hospital Income');
            $table->date('q_targLim_dateUpdate');
            $table->date('q_targLim_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_limits');
    }
}
