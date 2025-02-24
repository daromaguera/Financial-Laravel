<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->increments('q_tdo_id');
            $table->char('q_tdo_clientID', 64);
            $table->integer('q_tdo_agentID')->nullable();
            $table->integer('q_tdo_isForClientAgent')->comment('1 - Client, 2 - Agent');
            $table->text('q_tdo_descripx');
            $table->date('q_tdo_dateTodo');
            $table->char('q_tdo_fromTable', 32)->comment('This column specifies what table this record come from. Record or data mostly come from Financial Planning Solution.');
            $table->integer('q_tdo_isSeen')->unsigned()->comment('0 - No, 1 - Yes');
            $table->date('q_tdo_dateCreated');
            $table->date('q_tdo_dateMarkedAsResolved')->nullable();
            $table->text('q_tdo_remarksOnResolved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
