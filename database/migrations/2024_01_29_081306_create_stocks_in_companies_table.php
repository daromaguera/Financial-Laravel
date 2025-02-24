<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks_in_companies', function (Blueprint $table) {
            $table->increments('q_stoComp_id');
            $table->char('q_stoComp_clientID', 64);
            $table->text('q_stoComp_companyAlias');
            $table->integer('q_stoComp_noOfShares')->nullable();
            $table->double('q_stoComp_currentBookValueShare', 15, 2)->nullable();
            $table->double('q_stoComp_estimatedValue', 15, 2);
            $table->tinyInteger('q_stoComp_purpose')->comment('1 - Retirement, 2 - Education, 3 - Others');
            $table->tinyInteger('q_stoComp_exclusiveConjugal')->comment('1 - Exclusive, 2 - Conjugal');
            $table->integer('q_stoComp_shareSelf');
            $table->integer('q_stoComp_shareSpouse')->nullable();
            $table->tinyInteger('q_stoComp_isListed')->comment('1 - listed, 0 - Non listed');

            $table->char('q_stoComp_accNo', 64)->nullable();
            $table->tinyInteger('q_stoComp_insuProd')->nullable();
            $table->integer('q_stoComp_projRate')->nullable();
            $table->double('q_stoComp_projValEducAge', 15, 2)->nullable();
            $table->double('q_stoComp_regPayoutAmt', 15, 2)->nullable();
            $table->integer('q_stoComp_ageStartPayout')->nullable();
            $table->integer('q_stoComp_startYearForPayout')->nullable();
            $table->integer('q_stoComp_freqOfPayout')->nullable();
            $table->integer('q_stoComp_ageChildForLastPayout')->nullable();
            $table->integer('q_stoComp_endYearForPayout')->nullable();
            
            $table->date('q_stoComp_dateUpdated');
            $table->date('q_stoComp_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks_in_companies');
    }
}
