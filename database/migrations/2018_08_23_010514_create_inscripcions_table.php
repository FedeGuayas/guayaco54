<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscripcionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('escenario_id')->unsigned()->nullable();//escenario donde el empleado hizo la inscripcion, online=>null
            $table->integer('producto_id')->unsigned();//categoria, circuito,
            $table->integer('persona_id')->unsigned();//perfil del cliente inscrito
            $table->integer('user_id')->unsigned()->nullable(); //empleado que realizo la inscripcion, si es online esta sera null
            $table->integer('user_edit')->unsigned()->nullable(); //empleado que cambia el estado y forma de pago
            $table->integer('deporte_id')->nullable()->unsigned(); //deportista costo es 0 y no se da camiseta solo numero
            $table->integer('factura_id')->unsigned()->nullable();//si es deportista la factura es null
            $table->dateTime('fecha'); //fecha de aprobada la inscripcion, created_at es la de preinscripcion
            $table->string('num_corredor')->nullable();//guardar el numero para ver en la historia con k num participo
            $table->string('kit')->nullable();//si se entrego el kit=1, no entregado=null
            $table->string('talla_id',20)->nullable(); //talla de camiseta
            $table->integer('costo');//valor neto que pago por la inscripcion
            $table->integer('ejercicio_id')->unsigned();//año de la inscripcion
            $table->string('status')->default(\App\Inscripcion::RESERVADA);

            $table->timestamps();

            $table->unique(['persona_id', 'ejercicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscripcions');
    }
}
