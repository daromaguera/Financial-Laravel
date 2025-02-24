<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirePlannFNASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retire_plann_f_n_a_s', function (Blueprint $table) {
            $table->increments('q_retPFNA_id');
            $table->char('q_retPFNA_clientID', 64);
            $table->text('q_retPFNAa_resRetPlann')->nullable()->comment('Reason why retirement is important');
            $table->text('q_retPFNA_howRetLooks')->nullable()->comment('How retirement looks like for the client');
            $table->integer('q_retPFNA_currAgeCL')->nullable();
            $table->integer('q_retPFNA_currAgeSP')->nullable();
            $table->integer('q_retPFNA_ageRetCL')->nullable()->comment('Start age of retirement for Client');
            $table->integer('q_retPFNA_ageRetSP')->nullable()->comment('Start age of retirement for Spouse');
            $table->integer('q_retPFNA_lifeSpanCL')->nullable()->comment('Estimated lifespan for Client');
            $table->integer('q_retPFNA_lifeSpanSP')->nullable()->comment('Estimated lifespan for Spouse');
            $table->integer('q_retPFNA_avgInfaRate')->nullable()->comment('Average Inflation Rate');
            $table->integer('q_retPFNA_intRetirement')->nullable()->comment('Interest Rate at Retirement');
            $table->double('q_retPFNA_sssAnnualCL', 15, 2)->nullable()->comment('SSS Annual Benefit for Client');
            $table->double('q_retPFNA_sssAnnualSP', 15, 2)->nullable()->comment('SSS Annual Benefit for Client');
            $table->integer('q_retPFNA_yrsSSSBenefitCL')->nullable()->comment('How many years will the Client receives benefit?');
            $table->integer('q_retPFNA_yrsSSSBenefitSP')->nullable()->comment('How many years will the Spouse receives benefit?');
            $table->double('q_retPFNA_companyBenefitRetCL', 15, 2)->nullable()->comment('Company Benefit Retirement for Client');
            $table->double('q_retPFNA_companyBenefitRetSP', 15, 2)->nullable()->comment('Company Benefit Retirement for Spouse');
            $table->integer('q_retPFNA_yrsCompanyBenefitCL')->nullable()->comment('How many years will the Client receives company benefit?');
            $table->integer('q_retPFNA_yrsCompanyBenefitSP')->nullable()->comment('How many years will the Spouse receives company benefit?');
            $table->date('q_retPFNA_dateUpdated');
            $table->date('q_retPFNA_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retire_plann_f_n_a_s');
    }
}
