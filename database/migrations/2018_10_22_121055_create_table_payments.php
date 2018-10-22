<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('status'); // transaction.status = 0,1,2,4
            $table->string('order_description'); //transaction.order_description = 'GR-2018 #factura.numero',
            $table->string('authorization_code'); //transaction.authorization_code => El código de autorización de la transacción enviada por el carrier.
            $table->unsignedTinyInteger('status_detail'); // transaction.status_detail = 0-34 => El detalle del status de la transaction
            $table->date('date'); //transaction.date => Fecha de la transaction  '19/10/2018 15:30:35',
            $table->string('message'); // transaction.message =  "Operation Successful", Mensaje de respuesta del carrier
            $table->string('transaction_id')->unique(); //transaction.id = "CI-502", Este código es único entre todas las transacciones.
            $table->string('dev_reference'); // transaction.dev_reference = orden de compra (factura_id) = order_reference en checkout modal
            $table->unsignedTinyInteger('carrier_code'); // transaction.carrier_code El código del mensaje de retorno.
            $table->decimal('amount',4,2);//transaction.amount = 10.5 , Importe a debitar.
            $table->date('paid_date'); //transaction.paid_date => Fecha de pago de la transacción   '19/10/2018 15:30:00',
            $table->unsignedTinyInteger('installments'); // transaction.installments = 0 (rotativo) El tipo de cuotas
            $table->string('stoken'); //transaction.stoken Hash MD5 de [transaction_id] _ [application_code] _ [user_id] _ [app_key]
            $table->string('application_code'); //transaction.application_code La app code a la que pertenece la trnas, server o client
            $table->integer('user_id')->unsigned(); //user.id  => Identificador del cliente. Este es el identificador que usa dentro de su aplicación.
            $table->string('email'); //user.email => Email del comprador.
            $table->string('bin'); //card.bin => The credit card bin.
            $table->string('holder_name'); //card.holder_name => El nombre del titular de la tarjeta de crédito.
            $table->char('type'); //card.type = vi, mc,... => Tipo de tarjeta abreviado //https://paymentez.github.io/api-doc/#card-brands
            $table->string('number'); //card.number => Los últimos cuatro dígitos de la tarjeta de crédito.
            $table->string('origin'); //card.origin => El origen de la tarjeta de crédito. Podría ser uno de los siguientes: Paymentez, VisaCheckout, Masterpass

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
        Schema::dropIfExists('payments');
    }
}
