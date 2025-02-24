<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('q_ADm_id');
            $table->text('q_ADm_token')->comment('Token string');
            $table->char('q_ADm_type', 18)->comment('S - Super Admin, X - Company Admin, M - Manager');
            $table->char('q_ADm_fN', 32)->comment('Admin First Name');
            $table->char('q_ADm_lN', 32)->comment('Admin Last Name');
            $table->char('q_ADm_mN', 32)->comment('Admin Middle Name');
            $table->text('q_ADm_addrx')->nullable();
            $table->text('q_ADm_profileImage');
            $table->dateTime('q_ADm_successfulDateSync');
            $table->date('q_ADm_lastLoggedIn');
            $table->integer('q_ADm_isActive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
