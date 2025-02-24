<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducPlannExpListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educ_plann_exp_lists', function (Blueprint $table) {
            $table->increments('q_educPExpList_id');
            $table->text('q_educPExpList_description');
            $table->tinyInteger('q_educPExpList_isOther')->comment('1 - Yes, 0 - No. Rows marked with "other" (1) is solely belong to a particular client');
            $table->integer('q_educPExpList_order');
            $table->date('q_educPExpList_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educ_plann_exp_lists');
    }
}
