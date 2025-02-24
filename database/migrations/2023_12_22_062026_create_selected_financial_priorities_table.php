<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedFinancialPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_financial_priorities', function (Blueprint $table) {
            $table->increments('q_sfp_id');
            $table->char('q_sfp_clnt_id', 64);
            $table->integer('q_sfp_fp_id')->nullable();
            $table->integer('q_sfp_rank')->unsigned();
            $table->longText('q_sfp_reason')->nullable();
            $table->date('q_sfp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_financial_priorities');
    }
}
