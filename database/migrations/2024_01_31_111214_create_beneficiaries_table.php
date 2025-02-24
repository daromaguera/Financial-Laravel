<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->increments('q_benex_id');
            $table->integer('q_benex_lifeHeath_id');
            $table->char('q_benex_fullName', 64);
            $table->integer('q_benex_percentShare');
            $table->tinyInteger('q_benex_designation')->comment('1 - Revocable, 2 - Irrevocable');
            $table->tinyInteger('q_benex_priority')->comment('1 - Primary, 2 - Secondary');
            $table->date('q_benex_dateUpdated');
            $table->date('q_benex_dateCreated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiaries');
    }
}
