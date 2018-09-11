<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->char('num_doc')->unique();
            $table->string('gen')->nullable();
            $table->string('discapacitado')->default(\App\Persona::NO_DISCAPACITADO);
            $table->date('fecha_nac');
            $table->string('email',60)->nullable();//correo de facturacion
            $table->string('direccion')->nullable();
            $table->string('telefono',30)->nullable();
            $table->string('privado')->default(\App\Persona::PERFIL_PUBLICO);//si esta en 1 permite que usuarios asocien y vean su perfil, 0 no permitido, no aparece en las busquedas y elimina su perfil-asociado si ha sido asociado x alguna cuenta
            $table->string('estado')->default(\App\Persona::PERFIL_ACTIVO);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
