<?php

namespace App\Http\Controllers;

use App\Configuracion;
use App\Factura;
use App\Inscripcion;
use App\Mail\InscripcionPayOut;
use App\Payment;
use App\Registro;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Facades\App\Classes\LogActivity;
use function MongoDB\BSON\toJSON;

class PaymentController extends Controller
{

    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('getCallback');
    }

    /**
     *  Obtener los datos de la preinscripcion al dar en el boton pagar para actualizar form modal de payments (correo, tel, costo, etc)
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function getInscripcionPay(Request $request)
    {
        if ($request->ajax()) {

            $inscripcion = Inscripcion::with('factura', 'producto')
                ->where('id', $request->input('insc_id'))
                ->where('user_online', $request->user()->id)
                ->first();

            if ($inscripcion) {
                return response()->json(['data' => $inscripcion], 200);
            } else {
                return response()->json(['data' => 'No se encontró la inscripción'], 404);
            }

        }
        return false;
    }

    /**
     *
     * Actualizar estado de inscripcion y factura, asignar transaction_id a la factura , crear registro de corredor
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setFacturaTransID(Request $request)
    {
        if ($request->ajax()) {

            $transaction_id = $request->input('payID');
            $insc_id = $request->input('insc_id');

            $inscripcion = Inscripcion::with('factura', 'user', 'producto', 'persona')->where('id', $insc_id)->first();
            $factura = $inscripcion->factura;

//            if (!$inscripcion || !$factura) {
//                $notification = 'Se realizo su pago pero ocurrio un error al actualizar el estado de la inscripción, por favor pongase en contacto con nosotros proporcionando este ID de transacción ID: ' . $transaction_id . '';
//                return response()->json(['data' => $notification], 400);
//            }

            $factura->transaction_id = $transaction_id;
            $factura->status = Factura::PAGADA;
            $factura->update();

            try {

                DB::beginTransaction();

                //CREAR NUMERO DE CORREDOR
                $maxNumCorr = DB::table('registros')->max('numero'); //maximo valor en la columna numero
                if (is_numeric($maxNumCorr)) {
                    $nexNumCorredor = $maxNumCorr + 1;
                } else {
                    $maxNumCorr = 0;
                    $nexNumCorredor = 1;
                }

                $inscripcion->status = Inscripcion::RESERVADA;
                $inscripcion->num_corredor = $nexNumCorredor;
                $inscripcion->inscripcion_type = Inscripcion::INSCRIPCION_ONLINE;
                $inscripcion->update();

                $persona = $inscripcion->persona;

                //CREAR EL REGISTRO DEL CORREDOR
                $registro = new Registro();
                $registro->numero = $nexNumCorredor;
                $registro->inscripcion()->associate($inscripcion);
                $registro->persona()->associate($persona);
                $registro->save();

                DB::commit();

                $message = 'Se le enviará un correo con el estado de su pago.';
                return response()->json(['data' => $message], 200);

            } catch (\Exception $e) {
                DB::rollback();
//              $message=$e->getMessage();
                $message = 'Lo sentimos ha ocurrido un error, pongase en contacto con nosotros';
                return response()->json(['data' => $message], 400);
            }

        }
    }



    public function getCallback(Request $request)
    {
        $configuracion = Configuracion::where('status', Configuracion::ATIVO)->first();

        //cada vez que se haga una transaction se debe responder con uno de los siguientes codigos para confirmar la recepcion del callback

        /*  200	success
            201	product_id error
            202	user_id error
            203	token error
            204	transaction_id already received  //
        */

        if ($request ) {

//            Log::info($request);

            $responsePaymentez = '';

            $app_code = '';
            $app_key = '';
            $app_code_server = $configuracion->server_app_code;
            $app_key_server = $configuracion->server_app_key;
            $app_code_client = $configuracion->client_app_code;
            $app_key_client = $configuracion->client_app_key;

            //transaction
            $status = $request->input('transaction.status');//int 0	Pending,  1	Approved ,2	Cancelled, 4 Rejected
            $order_description = $request->input('transaction.order_description');//string
            $authorization_code = $request->input('transaction.authorization_code');//string
            $dev_reference = $request->input('transaction.dev_reference');//string,   = order_reference = factura_id
            $carrier_code = $request->input('transaction.carrier_code');//int
            $status_detail = $request->input('transaction.status_detail');//short
            $installments = $request->input('transaction.installments');//int
            $amount = $request->input('transaction.amount');//double
            $paid_date = $request->input('transaction.paid_date');//string '19/10/2018 16:41:00',
            $date = $request->input('transaction.date');//string
            $message = $request->input('transaction.message');//s.ing
            $transaction_stoken = $request->input('transaction.stoken');//string
            $transaction_id = $request->input('transaction.id');//string
            $application_code = $request->input('transaction.application_code');//string


            //user
            $user_id = $request->input('user.id');//string
            $user_email = $request->input('user.email');//string

            //card
            $bin = $request->input('card.bin');//string
            $holder_name = $request->input('card.holder_name');//string
            $type = $request->input('card.type');//string
            $number = $request->input('card.number');//string
            $origin = $request->input('card.origin');//string

            if ($application_code == $app_code_client) {
                //desarrollo
                $app_code = $app_code_client;
                $app_key = $app_key_client;
            } else {
                //Produccion
                $app_code = $app_code_server;
                $app_key = $app_key_server;
            }

            //stoken generation
            $stoken_string = $transaction_id . "_" . $app_code . "_" . $user_id . "_" . $app_key;
            $stoken_gen = md5($stoken_string); //hash md5


            //Get Response
            $responsePaymentez = "Respuesta Paymentez: status: " . $status
                . " - " . $this->getStatusDescription($status)
                . " | status_detail: " . $status_detail
                . " - " . $this->getStatusDetailDescription($status_detail)
                . " | order_description: " . $order_description
                . " | authorization_code: " . $authorization_code
                . " | date: " . $date
                . " | message: " . $message
                . " | transaction_id: " . $transaction_id
                . " | dev_reference: " . $dev_reference
                . " | amount: " . $amount
                . " | paid_date: " . $paid_date
                . " | installments: " . $installments
                . " | stoken: " . $transaction_stoken
                . " | application_code: " . $application_code
                . " | user_id: " . $user_id
                . " | email: " . $user_email
                . " | bin: " . $bin
                . " | holder_name: " . $holder_name
                . " | type: " . $type
                . " | number: " . $number
                . " | origin: " . $origin;


            //para asegurarse de que el POST provino de Paymentez
            if ($transaction_stoken == $stoken_gen) {

                //Buscar en tabla facturas la orden de compra por el campo dev_reference (factura_id)
                $order = Factura::where('id', $dev_reference)->first();

                if (isset($order)) { //si existe la factura

                    //compruebe la transaction_id en la tabla paymentez para asegurarse de que no está obteniendo un POST duplicado.
                    $payment = Payment::where('transaction_id', $transaction_id)->first();

                    $inscripcion = Inscripcion::with('persona', 'producto')->where('factura_id', $order->id)->first();

//                    LogActivity::addToPaymentLog('Prueba de callback ', $payment, $responsePaymentez);

                    // si la transacción no existe
                    if (!isset($payment)) { //no se encuentra la transaccion, es primer post del callback sobre esta transaccion

                        //Approved,
                        if ($status == 1 && $order->status == Factura::PAGADA && $order->payment_status != Factura::PAYMENT_APPROVED) {

                            $payment = new Payment();
                            $payment->status = $status;
                            $payment->order_description = $order_description;
                            $payment->authorization_code = $authorization_code;
                            $payment->status_detail = $status_detail;
                            $payment->date = $date;
                            $payment->message = $message;
                            $payment->transaction_id = $transaction_id;
                            $payment->dev_reference = $dev_reference;
                            $payment->carrier_code = $carrier_code;
                            $payment->amount = $amount;
                            $payment->paid_date = $paid_date;
                            $payment->installments = $installments;
                            $payment->application_code = $application_code;
                            $payment->stoken = $transaction_stoken;
                            //user
                            $payment->user_id = $user_id;
                            $payment->email = $user_email;
                            //card
                            $payment->bin = $bin;
                            $payment->holder_name = $holder_name;
                            $payment->type = $type;
                            $payment->number = $number;
                            $payment->origin = $origin;
                            $payment->save();

                            $inscripcion->status = Inscripcion::PAGADA;
                            $inscripcion->update();

                            $order->payment_status = Factura::PAYMENT_APPROVED;
                            $order->update();

                            //email de confirmacion de compra al correo de facturacion del usuario
                            Mail::to($payment->email)->send(new InscripcionPayOut($inscripcion, $payment));

                            if (count(Mail::failures() < 0)) {
                                //correo enviado
                                LogActivity::addToPaymentLog('Enviado correo de confirmacion de pago', $payment, $responsePaymentez);
                            } else {
                                //correo no enviado
                                LogActivity::addToPaymentLog('Error al enviar correo de confirmacion de pago', $payment, $responsePaymentez);
                            }
                            LogActivity::addToPaymentLog('Pago confirmado', $payment, $responsePaymentez);
                            //200	success
                            return response()->json(['success' => 'Approved'], 200);

                        } else {
                            if ($status == 4) {  //Rejected

                                $payment = new Payment();
                                $payment->status = $status;
                                $payment->order_description = $order_description;
                                $payment->authorization_code = $authorization_code;
                                $payment->status_detail = $status_detail;
                                $payment->date = $date;
                                $payment->message = $message;
                                $payment->transaction_id = $transaction_id;
                                $payment->dev_reference = $dev_reference;
                                $payment->carrier_code = $carrier_code;
                                $payment->amount = $amount;
                                $payment->paid_date = $paid_date;
                                $payment->installments = $installments;
                                $payment->application_code = $application_code;
                                $payment->stoken = $transaction_stoken;
                                //user
                                $payment->user_id = $user_id;
                                $payment->email = $user_email;
                                //card
                                $payment->bin = $bin;
                                $payment->holder_name = $holder_name;
                                $payment->type = $type;
                                $payment->number = $number;
                                $payment->origin = $origin;
                                $payment->save();

                                $inscripcion->status = Inscripcion::RESERVADA;
                                $inscripcion->update();

                                $order->payment_status = Factura::PAYMENT_REJECTED;
                                $order->update();

                                LogActivity::addToPaymentLog('Pago rechazado', $payment, $responsePaymentez);

                                //200	success
                                return response()->json(['success' => 'Rejected'], 200);
                            }
                        }
                    } else { //existia el pago,  payment 0,	Pending ,2	Cancelled, 4 Rejected

                        if ( ($status == 0 || $status == 2 || $status == 4 ) ) {  //Cancelled -- Reversed

                            $payment->status = $status;
                            $payment->order_description = $order_description;
                            $payment->authorization_code = $authorization_code;
                            $payment->status_detail = $status_detail;
                            $payment->date = $date;
                            $payment->message = $message;
                            $payment->transaction_id = $transaction_id;
                            $payment->dev_reference = $dev_reference;
                            $payment->carrier_code = $carrier_code;
                            $payment->amount = $amount;
                            $payment->paid_date = $paid_date;
                            $payment->installments = $installments;
                            $payment->application_code = $application_code;
                            $payment->stoken = $transaction_stoken;
                            //user
                            $payment->user_id = $user_id;
                            $payment->email = $user_email;
                            //card
                            $payment->bin = $bin;
                            $payment->holder_name = $holder_name;
                            $payment->type = $type;
                            $payment->number = $number;
                            $payment->origin = $origin;
                            $payment->save();

                            $inscripcion->status = Inscripcion::RESERVADA; //eliminar inscripcion
                            $inscripcion->update();

                            //eliminar registro

                            //aumentar talla stock

                            $order->status = Factura::CANCELADA; //pago reverzado
                            $order->payment_status = Factura::PAYMENT_CANCELLED; //pago reverzado
                            $order->update();

                            LogActivity::addToPaymentLog('Pago reversado', $payment, $responsePaymentez);
                            //200	success
                            return response()->json(['success' => 'Cancelled'], 200);

                        } else { //204	transaction_id already received

                            //200	success
                            return response()->json(['success' => 'transaction_id already received'], 204);
                        }

                    }

                }  else {//201	product_id error

                    return response()->json(['success' => 'product_id error'], 201);
                }

            } else {//203	token error

                return response()->json(['success' => 'token error'], 203);
            }
        }

    }

    /**
     * Status de la transaccion
     * @param $status
     * @return string
     */
    //https://paymentez.github.io/api-doc/#status-details
    public function getStatusDescription($status)
    {
        $statusDescription = '';
        //reference on https://paymentez.github.io/api-doc/#status-details
        switch ($status) {
            case 1: {
                $statusDescription = "Approved";
                break;
            }
            case 2: {
                $statusDescription = "Cancelled";
                break;
            }
            case 4: {
                $statusDescription = "Rejected";
                break;
            }
        }

        return $statusDescription;
    }

    /**
     * Detalles del status de transaccion
     * @param $status_detail
     * @return string
     */
    //https://paymentez.github.io/api-doc/#status-details
    public function getStatusDetailDescription($status_detail)
    {
        $statusDetailDescription = '';

        switch ($status_detail) {
            case 0: {
                $statusDetailDescription = "Waiting for Payment.";
                break;
            }
            case 1: {
                $statusDetailDescription = "Verification required, please see Verification section.";
                break;
            }
            case 3: {
                $statusDetailDescription = "Paid";
                break;
            }
            case 6: {
                $statusDetailDescription = "Fraud";
                break;
            }
            case 7: {
                $statusDetailDescription = "Refund";
                break;
            }
            case 8: {
                $statusDetailDescription = "Chargeback";
                break;
            }
            case 9: {
                $statusDetailDescription = "Rejected by carrier";
                break;
            }
            case 10: {
                $statusDetailDescription = "System error";
                break;
            }
            case 11: {
                $statusDetailDescription = "Paymentez fraud";
                break;
            }
            case 12: {
                $statusDetailDescription = "Paymentez blacklist";
                break;
            }
            case 13: {
                $statusDetailDescription = "Time tolerance.";
                break;
            }
            case 19: {
                $statusDetailDescription = "Invalid Authorization Code.";
                break;
            }
            case 20: {
                $statusDetailDescription = "Authorization code expired.";
                break;
            }
            case 21: {
                $statusDetailDescription = "Paymentez Fraud - Pending refund.";
                break;
            }
            case 22: {
                $statusDetailDescription = "Invalid AuthCode - Pending refund.";
                break;
            }
            case 23: {
                $statusDetailDescription = "AuthCode expired - Pending refund.";
                break;
            }
            case 24: {
                $statusDetailDescription = "Paymentez Fraud - Refund requested.";
                break;
            }
            case 25: {
                $statusDetailDescription = "Invalid AuthCode - Refund requested.";
                break;
            }
            case 26: {
                $statusDetailDescription = "AuthCode expired - Refund requested.";
                break;
            }
            case 27: {
                $statusDetailDescription = "Merchant - Pending refund.";
                break;
            }
            case 28: {
                $statusDetailDescription = "Merchant - Refund requested.";
                break;
            }
            case 30: {
                $statusDetailDescription = "Transaction seated (only Datafast).";
                break;
            }
            case 31: {
                $statusDetailDescription = "Waiting for OTP.";
                break;
            }
            case 32: {
                $statusDetailDescription = "OTP successfully validated.";
                break;
            }
            case 33: {
                $statusDetailDescription = "OTP not validated.";
                break;
            }
            case 34: {
                $statusDetailDescription = "Partial refund";
                break;
            }

        }

        return $statusDetailDescription;
    }


    /**
     * Obtener los pagos online aprobados para realizar reembolsos y cancelaciones
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRefund(Request $request)
    {
        $user = $request->user();

        $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
            ->select('ejercicio_id')
            ->first();

        $comprobantes = Inscripcion::from('inscripcions as i')
            ->join('facturas as f', 'f.id', '=', 'i.factura_id')
            ->join('payments as p', 'p.transaction_id', '=', 'f.transaction_id')
            ->whereNotNull('i.user_online')
            ->whereNotNull('f.transaction_id')
            ->where('f.status',Factura::PAGADA)
            ->where('f.payment_status',Factura::PAYMENT_APPROVED)
            ->where('i.inscripcion_type',Inscripcion::INSCRIPCION_ONLINE)
            ->where('i.status',Inscripcion::PAGADA)
            ->where('i.ejercicio_id', $ejercicio->ejercicio_id)
            ->select('f.transaction_id','f.id as fId','p.date','p.paid_date','p.amount','i.id','i.factura_id','p.dev_reference','p.transaction_id','i.user_online','f.status','f.payment_status','i.inscripcion_type','i.status','i.ejercicio_id')
            ->get();

        return view('inscripcion.online.index-refund', compact('comprobantes'));
    }


    /**
     *  Realizar reembolso
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function setRefund(Request $request){

//        $inscripcion = Inscripcion::with('factura', 'producto')
//            ->where('id', $request->input('insc_id'))
//            ->where('user_online', $request->user()->id)
//            ->first();

        if ($request->ajax()) {

            $url = '';
            $transc_id = $request->input('transc_id');

            $order = Factura::where('transaction_id', $transc_id)->first();

            if (isset($order)) {

                if ($order->payment_status == Factura::PAYMENT_APPROVED) {
                    //reversar



                } else {
                    $message = "Error! No se pudo completar el reverso, el pago ya ha sido reversado o cancelado previamente!";
                    return response()->json(['data' => $message], 200);
                }


            }else {
                $message = "Error! El código de confirmación es incorrecto, la orden no existe!";
                return response()->json(['data' => $message], 404);
            }


        }


//            if ($inscripcion) {
//                return response()->json(['data' => $inscripcion], 200);
//            } else {
//                return response()->json(['data' => 'No se encontró la inscripción'], 404);
//            }


    }

    //generar el token paymentez
    public function paymentezGenerateToken(Request $request)
    {
        $configuracion = Configuracion::where('status', Configuracion::ATIVO)->first();

//        $unix_timestamp=(int)$request->input('unix_timestamp');
        $paymentez_server_application_code = $configuracion->server_app_code;
        $paymentez_server_app_key = $configuracion->server_app_key;
//        $paymentez_server_application_code = $configuracion->client_app_code;;
//        $paymentez_server_app_key = $configuracion->client_app_key;

//        $unix_timestamp=1540411771; //        UNIX TIMESTAMP: 1540411771

//        $paymentez_server_application_code = 'FEDE-EC-SERVER';
//        $paymentez_server_app_key = 'rQph9IKZPta4KhiOXXwCfvWco9Vml6';
        //UNIQ STRING: rQph9IKZPta4KhiOXXwCfvWco9Vml61540411771
        //UNIQ HASH: 7920e79a3b3a5169fc9e693c33ef8a6838c4536d0efaad1899be407569b7696c
        //AUTH TOKEN: RkVERS1FQy1TRVJWRVI7MTU0MDQxMTc3MTs3OTIwZTc5YTNiM2E1MTY5ZmM5ZTY5M2MzM2VmOGE2ODM4YzQ1MzZkMGVmYWFkMTg5OWJlNDA3NTY5Yjc2OTZj

        $unix_timestamp =(string)Carbon::now()->timestamp;
        $uniq_token_string = $paymentez_server_app_key.''. $unix_timestamp;

        $uniq_token_hash = hash('sha256', $uniq_token_string, false);

        $string=$paymentez_server_application_code.';'.$unix_timestamp.';'.$uniq_token_hash;
        $auth_token = base64_encode($string);

        return $auth_token;
    }

}
