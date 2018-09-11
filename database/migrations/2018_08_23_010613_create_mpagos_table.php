<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpagos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre'); //Tarjeta, WU, Efectivo
            $table->string('descripcion')->nullable();
            $table->string('status')->default(\App\Mpago::ACTIVO);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mpagos');
    }
}
