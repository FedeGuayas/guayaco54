<?php
/*
 * Productos define una carrera en una categoria y circuito
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('categoria_id')->unsigned();
            $table->integer('ejercicio_id')->unsigned();
            $table->integer('circuito_id')->unsigned();
            $table->text('description')->nullable(); // descripcion de la carrera, recorrido, etc
            $table->integer('price'); //precio del la carrera
            $table->string('image')->nullable();  //imagen del recorrido.

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
        Schema::dropIfExists('productos');
    }
}
