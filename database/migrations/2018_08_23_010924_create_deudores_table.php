<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeudoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deudores', function (Blueprint $table) {
            $table->increments('id');
            $table->char('num_doc', 10);
            $table->integer('persona_id')->unsigned(); //perfil
            $table->integer('user_id')->unsigned()->nullable(); //si no tiene cuenta de usuario es nulo
            $table->integer('chip');
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
        Schema::dropIfExists('deudores');
    }
}
