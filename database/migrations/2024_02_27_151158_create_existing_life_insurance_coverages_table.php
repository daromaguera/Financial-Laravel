<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExistingLifeInsuranceCoveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('existing_life_insurance_coverages', function (Blueprint $table) {
            $table->increments('q_exLifeInsCov_id');
            $table->char('q_exLifeInsCov_clientID', 64);
            $table->integer('q_exLifeInsCov_listID');
            $table->double('q_exLifeInsCov_amtClient', 15, 2);
            $table->double('q_exLifeInsCov_amtSpouse', 15, 2);
            $table->date('q_exLifeInsCov_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('existing_life_insurance_coverages');
    }
}
