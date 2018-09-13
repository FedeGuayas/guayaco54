<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaCircuitoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_circuito', function (Blueprint $table) {
            $table->integer('categoria_id')->unsigned();
            $table->integer('circuito_id')->unsigned();

            $table->foreign('categoria_id')->references('id')->on('categorias')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('circuito_id')->references('id')->on('circuitos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['categoria_id', 'circuito_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria_circuito');
    }
}
