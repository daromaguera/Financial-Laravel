<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('q_agnt_id')->nullable();
            $table->text('q_agnt_token')->nullable();
            $table->char('q_agnt_f_name', 32);
            $table->char('q_agnt_m_name', 32)->nullable();
            $table->char('q_agnt_l_name', 32);
            $table->text('q_agnt_addrx')->nullable();
            $table->text('q_agnt_profileImage')->nullable();
            $table->dateTime('q_agnt_successfulDateSync');
            $table->text('q_agnt_linkLastVisited')->nullable();
            $table->date('q_agnt_lastLoggedIn')->nullable();
            $table->integer('q_agnt_isActive')->nullable();
            $table->char('q_agnt_uType', 16)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
