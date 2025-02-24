<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialPlannSolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_plann_sols', function (Blueprint $table) {
            $table->increments('q_finPlSo_id');
            $table->char('q_finPlSo_clientID', 64);
            $table->char('q_finPlSo_forTable', 32);
            $table->double('q_finPlSo_monthlyBud1',15,2)->comment('Monthly Budget for a Specific Fund of Client');
            $table->double('q_finPlSo_monthlyBud2',15,2)->comment('Monthly Budget for a Specific Fund of Spouse');
            $table->double('q_finPlSo_actNetCashflow1',15,2)->nullable()->comment('Total Monthly Actual Net Cash Flow of Client (optional)');
            $table->double('q_finPlSo_actNetCashflow2',15,2)->nullable()->comment('Total Monthly Actual Net Cash Flow of Spouse (optional)');
            $table->char('q_finPlSo_modePayment',8)->nullable();
            $table->char('q_finPlSo_formPayment',32)->nullable();
            $table->text('q_finPlSo_advisorSuggestion');
            $table->integer('q_finPlSo_status')->comment('1 - For Proposal Generation, 2 - Proposal For Review, 3 - Application Form Submitted - For Payment, 4 - Application Form Submitted - Paid');
            $table->integer('q_finPlSo_goalRev')->comment('0 - No, 1 - Yes, 3 - Not Applicable');
            $table->date('q_finPlSo_meetAdvisorOn')->nullable()->comment('The Client agree on a Date when to meet the advisor');
            $table->date('q_finPlSo_dateUpdated');
            $table->date('q_finPlSo_dateCreated');
            $table->date('q_finPlSo_dateMarkedAsResolved')->nullable();
            $table->text('q_finPlSo_remarksOnResolved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_plann_sols');
    }
}
