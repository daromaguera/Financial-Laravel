<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthCovSummsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_cov_summs', function (Blueprint $table) {
            $table->increments('q_healthCovSum_id');
            $table->char('q_healthCovSum_clientID', 64)->comment('ID of Client, just in case.');
            $table->integer('q_healthCovSum_famCompID')->comment('ID from Family Composition');
            $table->integer('q_healthCovSum_type')->comment('0 - Client, 1 - Partner (any), 2 - Spouse, 3 - Child, 4 - Father, 5 - Mother');
            $table->char('q_healthCovSum_policyRefNo', 64)->comment('Health Benefits for Policy/Reference No.');
            $table->integer('q_healthCovSum_origin')->comment('1 - from Aetos Advisor, 2 - Otherwise');
            $table->double('q_healthCovSum_amtInPatient', 15, 2)->nullable();
            $table->char('q_healthCovSum_opInPatient', 16)->nullable();
            $table->double('q_healthCovSum_amtOutPatient', 15, 2)->nullable();
            $table->char('q_healthCovSum_opOutPatient', 16)->nullable();
            $table->double('q_healthCovSum_amtCritIllLim', 15, 2)->nullable();
            $table->char('q_healthCovSum_opCritIllLim', 16)->nullable();
            $table->double('q_healthCovSum_amtLabLim', 15, 2)->nullable()->comment('Laboratory Limit');
            $table->double('q_healthCovSum_amtHospIncome', 15, 2)->nullable()->comment('Hospital Income');
            $table->integer('q_healthCovSum_maxNoDays')->nullable();
            $table->text('q_healthCovSum_notes')->nullable();
            $table->date('q_healthCovSum_dateUpdated');
            $table->date('q_healthCovSum_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_cov_summs');
    }
}
