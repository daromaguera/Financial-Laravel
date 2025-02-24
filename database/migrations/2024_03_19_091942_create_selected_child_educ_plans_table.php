<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedChildEducPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_child_educ_plans', function (Blueprint $table) {
            $table->increments('q_selChildEduP_id');
            $table->integer('q_selChildEduP_famComp_id')->comment('From Family Composition - ID of Child');
            $table->text('q_selChildEduP_desiredSchool')->nullable();
            $table->integer('q_selChildEduP_childAgeCollege')->nullable();
            $table->double('q_selChildEduP_totalEducFundNeeded', 15, 2)->nullable();
            $table->double('q_selChildEduP_investmentAlloc', 15, 2)->nullable();
            $table->date('q_selChildEduP_dateUpdated');
            $table->date('q_selChildEduP_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_child_educ_plans');
    }
}
