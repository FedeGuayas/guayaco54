<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero')->unique();//numero de factura
            $table->dateTime('fecha_edit'); //fecha de edicion del estado de la factura
            $table->integer('descuento')->default(0);//descuento total aplicado, suma del descuento de cada item
            $table->integer('subtotal');//valor sin descuento
            $table->integer('total');//valor incluido descuentos
            $table->integer('user_id')->nullable();//empleado que hizo la inscripcion null si es online
            $table->integer('persona_id')->nullable();//cliente a facturar
            $table->string('nombre')->nullable();//SI personalisa datos facturacion
            $table->string('email')->nullable();//SI personalisa datos facturacion
            $table->string('direccion')->nullable();//SI personalisa datos facturacion
            $table->string('telefono')->nullable();//SI personalisa datos facturacion
            $table->string('identificacion')->nullable();//SI personalisa datos facturacion
            $table->unsignedTinyInteger('mpago_id');//forma de pago
            $table->string('payment_id')->unique()->nullable();//id de la compra proporcionado por payment si es online sino es null
            $table->string('status')->default(\App\Factura::ACTIVA);

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
        Schema::dropIfExists('facturas');
    }
}
