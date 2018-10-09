<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserOnlineInscripcionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscripcions', function (Blueprint $table) {
            $table->integer('user_online')->unsigned()->nullable()->after('inscripcion_type'); //usuario online que realizo la inscripcion

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscripcions', function (Blueprint $table) {
            $table->dropColumn('user_online');
        });
    }
}
