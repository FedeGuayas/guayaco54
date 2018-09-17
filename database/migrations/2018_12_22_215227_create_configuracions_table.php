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
            $table->integer('ejercicio_id')->unsigned();//ejercicio activo
            $table->integer('impuesto_id')->unsigned();//iva activo
            $table->string('empresa'); //nombre de la empresa
            $table->string('telefonos');//telefonos de la empresa poner en front-end
            $table->string('ruc');//
            $table->string('email');//email de contacto info@fedeguayas.com.ec poner en front-end
            $table->string('direccion');//direccion de la empresa poner en front-end
            $table->string('nombre_contacto'); //nombre de la empresa
            $table->string('status')->default(\App\Configuracion::ATIVO);

            $table->foreign('ejercicio_id')->references('id')->on('ejercicios')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('impuesto_id')->references('id')->on('impuestos')
                ->onUpdate('cascade')->onDelete('restrict');
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
