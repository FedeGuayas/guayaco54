<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsPaymentezConfiguracionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracions', function (Blueprint $table) {
            $table->string('client_app_code')->nullable()->after('nombre_contacto'); //app_code paymentez
            $table->string('client_app_key',100)->nullable()->after('client_app_code'); //app_key paymentez
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracions', function (Blueprint $table) {
            $table->dropColumn(['client_app_code','client_app_key']);
        });
    }
}
