<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_type')->nullable(); //roles de usuario (separados por coma)
            $table->unsignedInteger('user_id')->nullable(); //user_id (trabajador y online)
            $table->string('subject'); //Se actualizo un registro ....
            $table->text('old_values')->nullable(); //valor anterior
            $table->text('new_values')->nullable(); //nuevo valor
            $table->text('url')->nullable(); //url
            $table->string('method'); //metodo de acceso
            $table->ipAddress('ip_address')->nullable(); //direccion ip
            $table->string('user_agent')->nullable(); //navegador utilizado
            $table->timestamps();

            $table->index(['user_id', 'user_type']);

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('log_activities');
    }
}
