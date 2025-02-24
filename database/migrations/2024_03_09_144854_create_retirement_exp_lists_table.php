<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirementExpListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirement_exp_lists', function (Blueprint $table) {
            $table->increments('q_retExpList_id');
            $table->text('q_retExpList_description');
            $table->integer('q_retExpList_isOther')->comment('1 - Yes, 0 - No. Rows marked with "other" (1) is solely belong to a particular client');
            $table->date('q_retExpList_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retirement_exp_lists');
    }
}
