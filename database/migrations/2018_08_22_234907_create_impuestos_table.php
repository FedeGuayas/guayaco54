<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImpuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('impuestos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');//nombre tipo impuesto Ej: IVA,etc
            $table->unsignedTinyInteger('porciento');//porcentaje 12 %
            $table->unsignedTinyInteger('divisor');//divisor   IVA 12%=>1.12  14%=>1.14
            $table->string('status')->default(\App\Impuesto::ACTIVO);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('impuestos');
    }
}
