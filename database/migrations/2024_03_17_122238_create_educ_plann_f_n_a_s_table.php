<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducPlannFNASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educ_plann_f_n_a_s', function (Blueprint $table) {
            $table->increments('q_educPFNA_id');
            $table->char('q_educPFNA_clientID', 64);
            $table->text('q_educPFNA_resEducPlannImp')->nullable()->comment('Reason why Education Planning is Important');
            $table->text('q_educPFNA_dreamsForChildren')->nullable();
            $table->date('q_educPFNA_dateUpdated');
            $table->date('q_educPFNA_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educ_plann_f_n_a_s');
    }
}
