<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFnaCompletionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fna_completions', function (Blueprint $table) {
            $table->increments('q_fnaComp_id');
            $table->char('q_fnaComp_clientID', 64)->nullable();
            $table->char('q_fnaComp_FNA', 64);
            $table->integer('q_fnaComp_statusValue');
            $table->date('q_fnaComp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fna_completions');
    }
}
