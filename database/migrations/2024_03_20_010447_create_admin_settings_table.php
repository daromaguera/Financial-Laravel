<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->increments('q_admSett_id');
            $table->decimal('q_admSett_lastUpdatedByID',8,2)->nullable()->comment('Indicator to which admin who updated the value');
            $table->decimal('q_admSett_famProInflaRate',8,2)->nullable()->comment('Family Protection Inflation Rate');
            $table->decimal('q_admSett_retInflationRate',8,2)->nullable()->comment('Retirement Inflation Rate');
            $table->decimal('q_admSett_retEstInterestRate',8,2)->nullable()->comment('Retirement Estimated Interest Rate Upon Retirement');
            $table->decimal('q_admSett_childEducInflaRate',8,2)->nullable()->comment('Child Education - Average Inflation Rate of Tuition Fees');
            $table->decimal('q_admSett_estateConvCurrTaxRate',8,2)->nullable()->comment('Estate Conservation - Current Estate Tax Rate');
            $table->decimal('q_admSett_estateConvOtherExpenses',8,2)->nullable()->comment('Estate Conservation - Other Estate Expenses');
            $table->integer('q_admSett_ageChildGoCollege')->nullable()->comment('Default age of child going to college');
            $table->date('q_admSett_dateUpdated');
            $table->date('q_admSett_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_settings');
    }
}
