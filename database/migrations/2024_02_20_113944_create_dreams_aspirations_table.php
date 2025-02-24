<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDreamsAspirationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dreams_aspirations', function (Blueprint $table) {
            $table->increments('q_dreAsp_id');
            $table->char('q_dreAsp_client_id', 64);
            $table->tinyInteger('q_dreAsp_goals')->comment('1 - Settlement of Debt, 2 - Travel, 3 - House, 4 - Car, 5 - Others');
            $table->text('q_dreAsp_otherGoals')->nullable()->comment('If "Others" was picked in "q_dreAsp_goals", specify the goal in text');
            $table->double('q_dreAsp_typeTargetAmount', 15, 2);
            $table->char('q_dreAsp_timeline', 32);
            $table->date('q_dreAsp_dateUpdated');
            $table->date('q_dreAsp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dreams_aspirations');
    }
}
