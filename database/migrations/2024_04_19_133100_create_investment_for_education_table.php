<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentForEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_for_education', function (Blueprint $table) {
            $table->increments('q_invForEduc_id');
            $table->integer('q_invForEduc_tableID');
            $table->char('q_invForEduc_fromTable', 32)->comment('This field defines the parent or source table');
            $table->integer('q_invForEduc_withWithoutPayout')->comment('1 - With Payout, 2 - Without Payout');
            $table->char('q_invForEduc_policy_no', 32);
            $table->char('q_invForEduc_type', 32);
            $table->text('q_invForEduc_company')->nullable();
            $table->double('q_invForEduc_cashSurrValue', 15, 2);
            $table->integer('q_invForEduc_isInsProduct')->comment('1 - Yes, 0 - No');
            $table->integer('q_invForEduc_rateOfReturn')->nullable();
            $table->double('q_invForEduc_valueEducAge', 15, 2)->nullable();
            $table->integer('q_invForEduc_ageChildPayout')->nullable();
            $table->double('q_invForEduc_valAfterCollege', 15, 2)->nullable();
            $table->double('q_invForEduc_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_invForEduc_startYearPayout')->nullable();
            $table->integer('q_invForEduc_freqPayout')->nullable();
            $table->integer('q_invForEduc_ageChildLastPayout')->nullable();
            $table->integer('q_invForEduc_endYearPayout')->nullable();
            $table->date('q_invForEduc_dateUpdated');
            $table->date('q_invForEduc_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investment_for_education');
    }
}
