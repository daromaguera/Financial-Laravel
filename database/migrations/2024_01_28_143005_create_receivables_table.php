<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->increments('q_rec_id');
            $table->char('q_rec_clientID', 64);
            $table->char('q_rec_debtorName', 64)->nullable();
            $table->text('q_rec_loanPurpose')->nullable();
            $table->double('q_rec_estimatedValue', 15, 2);
            $table->integer('q_rec_percentCollectability');
            $table->tinyInteger('q_rec_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->integer('q_rec_shareSelf')->nullable();
            $table->integer('q_rec_shareSpouse')->nullable();
            $table->tinyInteger('q_rec_withCli')->comment('1 - Yes, 0 - No');
            $table->char('q_rec_renewalMonth', 32)->nullable();
            $table->date('q_rec_dateUpdated');
            $table->date('q_rec_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivables');
    }
}
