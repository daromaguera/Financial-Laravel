<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_compositions', function (Blueprint $table) {
            $table->increments('q_famComp_id');
            $table->char('q_famComp_clientID', 64);
            $table->char('q_famComp_firstName', 32);
            $table->char('q_famComp_lastName', 32)->nullable();
            $table->char('q_famComp_middleInitial', 11)->nullable();
            $table->tinyInteger('q_famComp_compType')->nullable()->comment('Family Composition Type: 0 - Spouse, 1 - Partner, 2 - Children, 3 - Father, 4 - Mother');
            $table->tinyInteger('q_famComp_withWithoutChildren')->nullable()->comment('1 - Yes (With Children), 0 - No (Without)');
            $table->date('q_famComp_dateMarried')->nullable();
            $table->date('q_famComp_birthDay')->nullable();
            $table->tinyInteger('q_famComp_healthCondition')->nullable()->comment('1 - Healthy, 2 - With Medical Condition, 3 - Person with Disability, 4 - Deceased');
            $table->tinyInteger('q_famComp_status')->nullable()->comment('1 - Legitimate, 2 - Illegitimate');
            $table->text('q_famComp_revocableLiving')->nullable();
            $table->text('q_famComp_revocableLast')->nullable();
            $table->date('q_famComp_dateUpdated');
            $table->date('q_famComp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_compositions');
    }
}
