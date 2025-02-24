<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthFundPlannFNASTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_fund_plann_f_n_a_s', function (Blueprint $table) {
            $table->increments('q_healthFP_id');
            $table->char('q_healthFP_clientID', 64);
            $table->text('q_healthFP_resHealthFund')->nullable()->comment('Health Fund is Important to the Client because');
            $table->text('q_healthFP_finSitWithIllMember')->nullable()->comment('Financial Situation having a Member with Illness');
            $table->text('q_healthFP_finImpact')->nullable()->comment('Financial Impact having Spouse with Illness');
            $table->date('q_healthFP_dateUpdated');
            $table->date('q_healthFP_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_fund_plann_f_n_a_s');
    }
}
