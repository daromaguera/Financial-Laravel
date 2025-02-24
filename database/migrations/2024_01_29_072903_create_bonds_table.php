<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonds', function (Blueprint $table) {
            $table->increments('q_bond_id');
            $table->char('q_bond_clientID', 64);
            $table->text('q_bond_issuer');
            $table->date('q_bond_maturityDate')->nullable();
            $table->double('q_bond_perValue', 15, 2)->nullable();
            $table->double('q_bond_estimatedValue', 15, 2);
            $table->tinyInteger('q_bond_purpose')->comment('1 - Retirement, 2 - Education, 3 - Others');
            $table->tinyInteger('q_bond_withGuaranteedPayout')->nullable()->comment('1 - Yes, 0 - No');
            $table->tinyInteger('q_bond_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->integer('q_bond_shareSelf');
            $table->integer('q_bond_shareSpouse')->nullable();

            $table->char('q_bond_accNo', 64)->nullable();
            $table->tinyInteger('q_bond_insuProd')->nullable();
            $table->integer('q_bond_projRate')->nullable();
            $table->double('q_bond_projValEducAge', 15, 2)->nullable();
            $table->double('q_bond_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_bond_ageStartPayout')->nullable();
            $table->integer('q_bond_startYearForPayout')->nullable();
            $table->integer('q_bond_freqOfPayout')->nullable();
            $table->integer('q_bond_ageChildForLastPayout')->nullable();
            $table->integer('q_bond_endYearForPayout')->nullable();

            $table->date('q_bond_dateUpdated');
            $table->date('q_bond_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonds');
    }
}
