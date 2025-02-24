<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAndDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_and_deposits', function (Blueprint $table) {
            $table->increments('q_cad_id');
            $table->char('q_cad_clientID', 64);
            $table->char('q_cad_bank', 64);
            $table->text('q_cad_accountDescription')->nullable();
            $table->text('q_cad_typeOfAccount')->nullable();
            $table->double('q_cad_estimatedValue', 15, 2);
            $table->tinyInteger('q_cad_purpose')->comment('1 - retirement, 2 - Education, 3 - Others');
            $table->tinyInteger('q_cad_withGuaranteedPayout')->nullable()->comment('1 - Yes, 0 - No');
            $table->tinyInteger('q_cad_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->integer('q_cad_shareSelf')->nullable();
            $table->integer('q_cad_shareSpouse')->nullable();
            
            $table->char('q_cad_accNo', 64)->nullable();
            $table->tinyInteger('q_cad_insuProd')->nullable();
            $table->integer('q_cad_projRate')->nullable();
            $table->double('q_cad_projValEducAge', 15, 2)->nullable();
            $table->double('q_cad_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_cad_ageStartPayout')->nullable();
            $table->integer('q_cad_startYearForPayout')->nullable();
            $table->integer('q_cad_freqOfPayout')->nullable();
            $table->integer('q_cad_ageChildForLastPayout')->nullable();
            $table->integer('q_cad_endYearForPayout')->nullable();

            $table->date('q_cad_dateUpdated');
            $table->date('q_cad_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_and_deposits');
    }
}
