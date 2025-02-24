<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeAndHealthInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('life_and_health_insurances', function (Blueprint $table) {
            $table->increments('q_lifeHealth_id');
            $table->char('q_lifeHealth_clientID', 64);
            $table->tinyInteger('q_lifeHealth_fromAetosAdviser')->comment('1 - Yes, 2 - No');
            $table->char('q_lifeHealth_insuranceCompany', 64)->nullable();
            $table->tinyInteger('q_lifeHealth_policyOwner')->nullable()->comment('1 - Yes, 2 - No');
            $table->char('q_lifeHealth_policyNumber', 64)->nullable();
            $table->char('q_lifeHealth_policyStatus', 64)->nullable();
            $table->integer('q_lifeHealth_typeOfPolicy')->nullable()->comment('1 - VUL, 2 - Traditional, 3 - HMO, 4 - Pre-need, 5 - Others');
            $table->char('q_lifeHealth_monthYearIssued', 32)->nullable()->comment('Separate info by comma or dashed');
            $table->integer('q_lifeHealth_insured')->nullable()->comment('0 - Self, Other figures are for other dependents or family composition');
            $table->text('q_lifeHealth_purpose')->nullable()->comment('1 - Retirement, 2 - Education, 3 - Others'); // updated to string. Separate each strings with commas
            $table->tinyInteger('q_lifeHealth_withGuaranteedPayout')->comment('1 - Yes, 2 - No');
            $table->double('q_lifeHealth_faceAmountFamilyProtection', 15, 2)->nullable()->comment('Face amount for family protection/clean up fund');
            $table->double('q_lifeHealth_faceAmountEstateTax', 15, 2)->nullable();
            $table->double('q_lifeHealth_faceAmountEstateDistribution', 15, 2)->nullable();
            $table->double('q_lifeHealth_faceAmount', 15, 2)->nullable();
            $table->double('q_lifeHealth_currentFundValueEstimated', 15, 2)->nullable()->comment('Current account value or cash surrender value (estimated)');
           
            $table->char('q_lifeHealth_accNo', 64)->nullable();
            $table->tinyInteger('q_lifeHealth_insuProd')->nullable();
            $table->integer('q_lifeHealth_projRate')->nullable();
            $table->double('q_lifeHealth_projValEducAge', 15, 2)->nullable();
            $table->double('q_lifeHealth_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_lifeHealth_ageStartPayout')->nullable();
            $table->integer('q_lifeHealth_startYearForPayout')->nullable();
            $table->integer('q_lifeHealth_freqOfPayout')->nullable();
            $table->integer('q_lifeHealth_ageChildForLastPayout')->nullable();
            $table->integer('q_lifeHealth_endYearForPayout')->nullable();

            $table->date('q_lifeHealth_dateEffective');
            $table->date('q_lifeHealth_dateUpdated');
            $table->date('q_lifeHealth_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('life_and_health_insurances');
    }
}
