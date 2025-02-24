<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsAndFinalListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts_and_final_lists', function (Blueprint $table) {
            $table->increments('q_debtFin_debFinList_id');
            $table->text('q_debtFin_debFinList_desc')->comment('Description of Debts and Final List');
            $table->integer('q_debtFin_isOtherCreated')->comment('0 - No, 1 - Yes. Rows marked with "other" (1) is solely belong to a particular client');
            $table->integer('q_debtFin_order');
            $table->date('q_debtFin_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts_and_final_lists');
    }
}
