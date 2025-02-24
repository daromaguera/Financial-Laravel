<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->char('q_clnt_id', 64)->nullable();
            $table->integer('q_clnt_agnt_id')->nullable();
            $table->integer('q_clnt_fneeds_id')->nullable();
            $table->integer('q_clnt_spouseID')->nullable();
            $table->char('q_clnt_f_name', 64);
            $table->char('q_clnt_m_name', 64)->nullable();
            $table->char('q_clnt_l_name', 64);
            $table->date('q_clnt_birthDate')->nullable();
            $table->char('q_clnt_gendr', 8);
            $table->char('q_clnt_contNo', 32)->nullable();
            $table->char('q_clnt_emailAddrx', 64)->nullable();
            $table->char('q_clnt_civilStatx', 16)->nullable();
            $table->tinyInteger('q_clnt_haveChildren')->comment('1 - Yes, 0 - No');
            $table->integer('q_clnt_shareToSpouse')->comment('1 - Yes, 0 - No');
            $table->date('q_clnt_weddDate')->nullable();
            $table->tinyInteger('q_clnt_healthCondi')->nullable()->comment('0 - Healthy, 1 - With Medical Condition, 2 - Person with Disability');
            $table->text('q_clnt_healthCondiDetail')->nullable();
            $table->tinyInteger('q_clnt_takeRiskAssessM')->nullable()->comment('1 - Yes, 0 - No');
            $table->char('q_clnt_risk_cap', 32)->nullable();
            $table->char('q_clnt_risk_attix', 32)->nullable();
            $table->dateTime('q_clnt_successfulDateSync');
            $table->date('q_clnt_lastLoggedIn')->nullable();
            $table->longText('q_clnt_TOKEN')->nullable();
            $table->tinyInteger('q_clnt_isActive')->nullable()->comment('1 - Yes, 0 - No');
            $table->date('q_clnt_addxDate');
            $table->date('q_clnt_updxDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
