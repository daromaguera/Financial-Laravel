<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_assets', function (Blueprint $table) {
            $table->increments('q_perAs_id');
            $table->char('q_perAs_clientID', 64);
            $table->char('q_perAs_item', 64)->nullable();
            $table->double('q_perAs_estimatedValue', 15, 2);
            $table->tinyInteger('q_perAs_purpose')->comment('1 - retirement, 2 - education, 3 - others');
            $table->tinyInteger('q_perAs_withGuaranteedPayout')->comment('1 - Yes, 0 - No');
            $table->tinyInteger('q_perAs_exclusiveConjugal')->nullable()->comment('1 - Conjugal, 2 - Exclusive');
            $table->integer('q_perAs_shareSelf');
            $table->integer('q_perAs_shareSpouse')->nullable();

            $table->char('q_perAs_accNo', 64)->nullable();
            $table->tinyInteger('q_perAs_insuProd')->nullable();
            $table->integer('q_perAs_projRate')->nullable();
            $table->double('q_perAs_projValEducAge', 15, 2)->nullable();
            $table->double('q_perAs_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_perAs_ageStartPayout')->nullable();
            $table->integer('q_perAs_startYearForPayout')->nullable();
            $table->integer('q_perAs_freqOfPayout')->nullable();
            $table->integer('q_perAs_ageChildForLastPayout')->nullable();
            $table->integer('q_perAs_endYearForPayout')->nullable();

            $table->date('q_perAs_dateUpdated');
            $table->date('q_perAs_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_assets');
    }
}
