<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInscripcionTypeTableInscripcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscripcions', function (Blueprint $table) {
            $table->string('inscripcion_type')->after('status')->default(\App\Inscripcion::INSCRIPCION_PRESENCIAL);
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
            $table->dropColumn('inscripcion_type');
        });
    }
}
