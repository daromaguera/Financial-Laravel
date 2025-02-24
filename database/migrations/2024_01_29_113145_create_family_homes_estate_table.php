<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyHomesEstateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_homesEstate', function (Blueprint $table) {
            $table->increments('q_homeEstate_id');
            $table->char('q_homeEstate_clientID', 64);
            $table->char('q_homeEstate_tctNumber', 64)->nullable();
            $table->char('q_homeEstate_cityMunLocation', 64);
            $table->char('q_homeEstate_areaSQM', 32)->nullable();
            $table->double('q_homeEstate_zoneValueEstimate', 15, 2)->nullable();
            $table->double('q_homeEstate_estimatedValue', 15, 2);
            $table->tinyInteger('q_homeEstate_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->tinyInteger('q_homeEstate_purpose')->nullable()->comment('1 - Retirement, 2 - Education, 3 - Others');
            $table->tinyInteger('q_homeEstate_withGuaranteedPayout')->comment('1 - Yes, 0 - No');
            $table->integer('q_homeEstate_shareSelf');
            $table->integer('q_homeEstate_shareSpouse')->nullable();
            $table->integer('q_homeEstate_withPropertyInsurance')->comment('1 - Yes, 2 - None');
            $table->char('q_homeEstate_renewalMonth', 16)->nullable();
            $table->tinyInteger('q_homeEstate_isHome')->comment('1 - Family Home, 2 - Real Estate');

            $table->char('q_homeEstate_accNo', 64)->nullable();
            $table->tinyInteger('q_homeEstate_insuProd')->nullable();
            $table->integer('q_homeEstate_projRate')->nullable();
            $table->double('q_homeEstate_projValEducAge', 15, 2)->nullable();
            $table->double('q_homeEstate_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_homeEstate_ageStartPayout')->nullable();
            $table->integer('q_homeEstate_startYearForPayout')->nullable();
            $table->integer('q_homeEstate_freqOfPayout')->nullable();
            $table->integer('q_homeEstate_ageChildForLastPayout')->nullable();
            $table->integer('q_homeEstate_endYearForPayout')->nullable();

            $table->date('q_homeEstate_dateUpdated');
            $table->date('q_homeEstate_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_homes');
    }
}
