<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liabilities', function (Blueprint $table) {
            $table->increments('q_lia_id');
            $table->char('q_lia_clientID', 64);
            $table->char('q_lia_creditorName', 64)->nullable();
            $table->tinyInteger('q_lia_type')->comment('1 - Personal, 2 - Business');
            $table->double('q_lia_totalUnpaidAmt', 15, 2)->nullable();
            $table->double('q_lia_annualInterestRate', 15, 2)->nullable();
            $table->double('q_lia_amtOfMRI', 15, 2)->nullable();
            $table->double('q_lia_uncovered', 15, 2)->nullable();
            $table->tinyInteger('q_lia_exclusiveConjugal')->comment('1 - Conjugal, 2 - Exclusive');
            $table->integer('q_lia_shareSelf')->nullable();
            $table->integer('q_lia_shareSpouse')->nullable();
            $table->date('q_lia_dateUpdated');
            $table->date('q_lia_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liabilities');
    }
}
