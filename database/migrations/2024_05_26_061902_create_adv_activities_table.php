<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adv_activities', function (Blueprint $table) {
            $table->increments('q_advAct_id');
            $table->integer('q_advAct_agentID')->nullable();
            $table->char('q_advAct_clientID', 32)->nullable();
            $table->text('q_advAct_actDescription');
            $table->date('q_advAct_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adv_activities');
    }
}
