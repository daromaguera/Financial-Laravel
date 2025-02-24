<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFPFNASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('f_p_f_n_a_s', function (Blueprint $table) {
            $table->increments('q_fpfna_id');
            $table->char('q_fpfna_clientID', 64);
            $table->text('q_fpfna_finImpDeceased')->comment('In the Event that you untimely pass away, what would be the financial impact to your family?');
            $table->integer('q_fpfna_avgInflaRate')->comment('Average Inflation Rate');
            $table->double('q_fpfna_annOutflowsCL', 15, 2)->comment('Total annual cash outflow of Client');
            $table->double('q_fpfna_annOutflowsSP', 15, 2)->comment('Total annual cash outflow of Spouse');
            $table->integer('q_fpfna_yearsFamSupp')->comment('No. of years your family needs your support.');
            $table->double('q_fpfna_annSuppFromCL', 15, 2)->comment('Annual support given of Client');
            $table->double('q_fpfna_annSuppFromSP', 15, 2)->comment('Annual support given of Spouse');
            $table->integer('q_fpfna_yearsSuppCL')->comment('No. of years the client plan to continue giving support after he/she is gone');
            $table->integer('q_fpfna_yearsSuppSP')->comment('No. of years the spouse plan to continue giving support after he/she is gone');
            $table->double('q_fpfna_addxLifeInsuCL', 15, 2)->comment('Additional Life Insurance Coverage Needed of Client');
            $table->double('q_fpfna_addxLifeInsuSP', 15, 2)->comment('Additional Life Insurance Coverage Needed of Spouse');
            $table->date('q_fpfna_dateUpdated');
            $table->date('q_fpfna_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('f_p_f_n_a_s');
    }
}
