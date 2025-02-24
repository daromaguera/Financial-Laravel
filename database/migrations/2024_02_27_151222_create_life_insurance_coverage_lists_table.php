<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeInsuranceCoverageListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('life_insurance_coverage_lists', function (Blueprint $table) {
            $table->increments('q_lifeInsCovList_id');
            $table->text('q_lifeInsCovList_debFinListDesc')->comment('Description of Final Insurance Coverage List');
            $table->integer('q_lifeInsCovList_isOtherCreated')->comment('1 - Yes, 0 - No. Rows marked with "other" (1) is solely belong to a particular client');
            $table->integer('q_lifeInsCovList_order');
            $table->date('q_lifeInsCovList_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('life_insurance_coverage_lists');
    }
}
