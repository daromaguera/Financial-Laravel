<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsAndFinalExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts_and_final_expenses', function (Blueprint $table) {
            $table->increments('q_debtFinExp_debFin_id');
            $table->char('q_debtFinExp_client_id', 64);
            $table->integer('q_debtFinExp_debFinList_id')->comment('Debts and Final List ID');
            $table->double('q_debtFinExp_amount_on_client', 15, 2);
            $table->double('q_debtFinExp_amount_on_spouse', 15, 2);
            $table->date('q_debtFinExp_dateUpdated');
            $table->date('q_debtFinExp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts_and_final_expenses');
    }
}
