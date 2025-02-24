<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->increments('q_recommx_id');
            $table->integer('q_recommx_cfa_id')->nullable();
            $table->text('q_recommx_recommxDetails');
            $table->integer('q_recommx_isInflowOutflow')->comment('1 - Inflow, 2 - Outflow');
            $table->text('q_recommx_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommendations');
    }
}
