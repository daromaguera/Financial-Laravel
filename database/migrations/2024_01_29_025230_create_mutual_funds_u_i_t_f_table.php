<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutualFundsUITFTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutual_funds_u_i_t_f', function (Blueprint $table) {
            $table->increments('q_uitf_id');
            $table->char('q_uitf_clientID', 64);
            $table->text('q_uitf_company');
            $table->integer('q_uitf_noOfUnits')->nullable(); 
            $table->double('q_uitf_currentValuePerUnits', 8, 2)->nullable();
            $table->double('q_uitf_estimatedValue', 15, 2);
            $table->tinyInteger('q_uitf_purpose')->comment('1 - Retirement, 2 - Education, 3 - Others');
            $table->tinyInteger('q_uitf_withGuaranteedPayout')->nullable()->comment('1 - Yes, 2 - No');
            $table->tinyInteger('q_uitf_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->integer('q_uitf_shareSelf');
            $table->integer('q_uitf_shareSpouse')->nullable();

            $table->char('q_uitf_accNo', 64)->nullable();
            $table->tinyInteger('q_uitf_insuProd')->nullable();
            $table->integer('q_uitf_projRate')->nullable();
            $table->double('q_uitf_projValEducAge', 15, 2)->nullable();
            $table->double('q_uitf_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_uitf_ageStartPayout')->nullable();
            $table->integer('q_uitf_startYearForPayout')->nullable();
            $table->integer('q_uitf_freqOfPayout')->nullable();
            $table->integer('q_uitf_ageChildForLastPayout')->nullable();
            $table->integer('q_uitf_endYearForPayout')->nullable();
            
            $table->date('q_uitf_dateUpdated');
            $table->date('q_uitf_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutual_funds_u_i_t_f_s');
    }
}
