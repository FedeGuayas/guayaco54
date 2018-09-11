<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('ejercicio_id');//ejercicio activo
            $table->integer('impuesto_id');//iva activo
            $table->string('empresa'); //nombre de la empresa
            $table->string('telefonos');//telefonos de contacto
            $table->string('ruc');//
            $table->string('email');//email de contacto
            $table->string('direccion');//direccion de la empresa
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracions');
    }
}
