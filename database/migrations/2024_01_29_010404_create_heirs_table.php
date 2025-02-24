<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heirs', function (Blueprint $table) {
            $table->increments('q_heir_id');
            $table->integer('q_heir_famComp_id');
            $table->integer('q_heir_tableID');
            $table->integer('q_heir_fromTable')->comment('1 - receivables, 2 - cash and deposits, 3 - mutual_funds_u_i_t_f, 4 - bonds, 5 - Stocks in Listed and Non-Listed Companies, 6 - Family Home and Real Estate, 7 - Vehicles, 8 - Personal Assets,'); 
            $table->integer('q_heir_indicatedPercentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heirs');
    }
}
