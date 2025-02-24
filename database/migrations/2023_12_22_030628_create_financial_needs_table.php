<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialNeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_needs', function (Blueprint $table) {
            $table->increments('q_fneeds_id');
            $table->text('q_fneeds_name');
            $table->text('q_fneeds_descripx');
            $table->text('q_fneeds_linkPath');
            $table->date('q_fneeds_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_needs');
    }
}
