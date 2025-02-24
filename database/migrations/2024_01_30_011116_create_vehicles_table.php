<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('q_vehicle_id');
            $table->char('q_vehicle_clientID', 64);
            $table->char('q_vehicle_plateNo', 32)->nullable();
            $table->char('q_vehicle_type', 64)->nullable();
            $table->double('q_vehicle_estimatedValue', 15, 2)->nullable();
            $table->tinyInteger('q_vehicle_exclusiveConjugal')->nullable()->comment('1 - Conjugal, 2 - Exclusive');
            $table->integer('q_vehicle_shareSelf');
            $table->integer('q_vehicle_shareSpouse');
            $table->tinyInteger('q_vehicle_withInsurance')->nullable()->comment('1 - Yes, 0 - None');
            $table->char('q_vehicle_renewalMonth', 16)->nullable();
            $table->char('q_vehicle_accNo', 64)->nullable();
            $table->tinyInteger('q_vehicle_insuProd')->nullable();
            $table->integer('q_vehicle_projRate')->nullable();
            $table->double('q_vehicle_projValEducAge', 15, 2)->nullable();
            $table->date('q_vehicle_dateUpdated');
            $table->date('q_vehicle_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
