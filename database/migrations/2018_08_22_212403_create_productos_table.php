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
            $table->string('status')->default(\App\Producto::ACTIVO);

            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('circuito_id')->references('id')->on('circuitos')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('ejercicio_id')->references('id')->on('ejercicios')
                ->onUpdate('cascade')->onDelete('restrict');

            $table->unique(['categoria_id', 'circuito_id','ejercicio_id']);
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
