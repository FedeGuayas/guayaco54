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

            $iva_conf=Configuracion::with('impuesto')->where('status',Configuracion::ATIVO)->first();

            $tax_div=(float)$iva_conf->impuesto->divisor;
            $tax_mult=($iva_conf->impuesto->porciento)/100;

            $inscripcion = Inscripcion::with('factura', 'producto')
                ->where('id', $request->input('insc_id'))
                ->where('user_online', $request->user()->id)
                ->first();

            $order_taxable_amount=$inscripcion->factura->total/$tax_div; // 8.00/1.12
            $order_vat=($inscripcion->factura->total/$tax_div)*$tax_mult; // (8.00/1.12)*0.12

            if ($inscripcion) {
                return response()->json(['data' => $inscripcion,'order_vat'=>$order_vat,'order_taxable_amount'=>$order_taxable_amount], 200);
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
            $factura->status = Factura::PAGADA;  //response del checkout success
            $factura->payment_status = Factura::PAYMENT_PENDING; //poner pendiente hasta recibir callback
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
        Log::info($request);
        $configuracion = Configuracion::where('status', Configuracion::ATIVO)->first();

        //cada vez que se haga una transaction se debe responder con uno de los siguientes codigos para confirmar la recepcion del callback
        /*  200	success
            201	product_id error
            202	user_id error
            203	token error
            204	transaction_id already received  //
        */

        /*
         * Satus del callback
            0	Pending
            1	Approved
            2	Cancelled -- Reversed
            4   Rejected
          */

        if ($request) {

            $responsePaymentez = '';

            $app_code = '';
            $app_key = '';
            $app_code_server = $configuracion->server_app_code;
            $app_key_server = $configuracion->server_app_key;
            $app_code_client = $configuracion->client_app_code;
            $app_key_client = $configuracion->client_app_key;

            $data = [
                //transaction
                'status' => $request->input('transaction.status'),
                'order_description' => $request->input('transaction.order_description'),
                'authorization_code' => $request->input('transaction.authorization_code'),
                'dev_reference' => $request->input('transaction.dev_reference'),// order_reference = factura_id
                'carrier_code' => $request->input('transaction.carrier_code'),
                'status_detail' => $request->input('transaction.status_detail'),
                'installments' => $request->input('transaction.installments'),
                'amount' => $request->input('transaction.amount'),
                'paid_date' => $request->input('transaction.paid_date'),
                'date' => $request->input('transaction.date'),
                'message' => $request->input('transaction.message'),
                'transaction_stoken' => $request->input('transaction.stoken'),
                'transaction_id' => $request->input('transaction.id'),
                'application_code' => $request->input('transaction.application_code'),
                //user
                'user_id' => $request->input('user.id'),
                'user_email' => $request->input('user.email'),
                //card
                'bin' => $request->input('card.bin'),
                'holder_name' => $request->input('card.holder_name'),
                'type' => $request->input('card.type'),
                'number' => $request->input('card.number'),
                'origin' => $request->input('card.origin')
            ];

            if ($data['application_code'] == $app_code_client) {
                //develop
                $app_code = $app_code_client;
                $app_key = $app_key_client;
            } else {
                //Production
                $app_code = $app_code_server;
                $app_key = $app_key_server;
            }

            //stoken generation
            $stoken_string = $data['transaction_id'] . "_" . $app_code . "_" . $data['user_id'] . "_" . $app_key;
            $stoken_gen = md5($stoken_string); //hash md5


            //Get Response text
            $responsePaymentez = "Respuesta Paymentez: status: " . $data['status']
                . " - " . $this->getStatusDescription($data['status'])
                . " | status_detail: " . $data['status_detail']
                . " - " . $this->getStatusDetailDescription($data['status_detail'])
                . " | order_description: " . $data['order_description']
                . " | authorization_code: " . $data['authorization_code']
                . " | date: " . $data['date']
                . " | message: " . $data['message']
                . " | transaction_id: " . $data['transaction_id']
                . " | dev_reference: " . $data['dev_reference']
                . " | amount: " . $data['amount']
                . " | paid_date: " . $data['paid_date']
                . " | installments: " . $data['installments']
                . " | stoken: " . $data['transaction_stoken']
                . " | application_code: " . $data['application_code']
                . " | user_id: " . $data['user_id']
                . " | email: " . $data['user_email']
                . " | bin: " . $data['bin']
                . " | holder_name: " . $data['holder_name']
                . " | type: " . $data['type']
                . " | number: " . $data['number']
                . " | origin: " . $data['origin'];

            //incoming post only for Paymentez
            if ($data['transaction_stoken'] == $stoken_gen) {

                //find order (factura) by dev_reference (factura_id)
                $order = Factura::where('id', $data['dev_reference'])->first();

                //exist order
                if (isset($order)) {

                    //check transaction_id paymentez table, secure not duplicate POST.
                    $payment = Payment::where('transaction_id', $data['transaction_id'])->first();

                    $inscripcion = Inscripcion::with('persona', 'producto')->where('factura_id', $order->id)->first();

                    // not exist transaction
                    if (!isset($payment)) {

                        //check callback response status
                        switch ($data['status']) {

                            case 1 : //Approved,
                                $subjet = 'Pago confirmado';
                                if ($order->status == Factura::PAGADA && $order->payment_status != Factura::PAYMENT_APPROVED) {
                                    $payment=$this->payment_save($data);
                                    $inscripcion->status = Inscripcion::PAGADA;
                                    $inscripcion->update();
                                    $order->payment_status = Factura::PAYMENT_APPROVED;
                                    $order->update();
                                    //email pay confirmation to user
                                    Mail::to($payment->email)->send(new InscripcionPayOut($inscripcion, $payment));
                                    if (count(Mail::failures() < 0)) {
                                        //send email is ok
                                        LogActivity::addToPaymentLog('Enviado correo de confirmacion de pago', $payment, $responsePaymentez);
                                    } else {
                                        //send email error
                                        LogActivity::addToPaymentLog('Error al enviar correo de confirmacion de pago', $payment, $responsePaymentez);
                                    }
                                }
                                break;
                            case 4 : //Rejected
                                $subjet = 'Pago rechazado';
                                $payment=$this->payment_save($data);
                                $inscripcion->status = Inscripcion::RESERVADA;
                                $inscripcion->update();
                                $order->payment_status = Factura::PAYMENT_REJECTED;
                                $order->update();
                                break;
                            case 0 : //Pending
                                $subjet = 'Pago pendiente';
                                $payment=$this->payment_save($data);
                                $inscripcion->status = Inscripcion::RESERVADA;
                                $inscripcion->update();
                                $order->payment_status = Factura::PAYMENT_PENDING;
                                $order->update();
                                break;
                            default:  // $status != 0 , 1, 4
                                $subjet = 'Status not found';
                                break;

                        }

                        LogActivity::addToPaymentLog($subjet, $payment, $responsePaymentez);

                        return response()->json(['success' => $subjet], 200);

                    } else { //exist transaction
                        //Refund, Cancel
                        if ($data['status'] == 2) {  //Cancelled -- Reversed
                            $subjet = 'Pago reversado';
                            $payment->status = $data['status'];
                            $payment->order_description = $data['order_description'];
                            $payment->authorization_code = $data['authorization_code'];
                            $payment->status_detail = $data['status_detail'];
                            $payment->date = $data['date'];
                            $payment->message = $data['message'];
                            $payment->transaction_id = $data['transaction_id'];
                            $payment->dev_reference = $data['dev_reference'];
                            $payment->carrier_code = $data['carrier_code'];
                            $payment->amount = $data['amount'];
                            $payment->paid_date = $data['paid_date'];
                            $payment->installments = $data['installments'];
                            $payment->application_code = $data['application_code'];
                            $payment->stoken = $data['transaction_stoken'];
                            //user
                            $payment->user_id = $data['user_id'];
                            $payment->email = $data['user_email'];
                            //card
                            $payment->bin = $data['bin'];
                            $payment->holder_name = $data['holder_name'];
                            $payment->type = $data['type'];
                            $payment->number = $data['number'];
                            $payment->origin = $data['origin'];
                            $payment->update();

                            //delete registro

                            //update talla stock

                            //delete inscription

                            //cancel factura

                            /* FOT TEST*/
                            $inscripcion->status = Inscripcion::RESERVADA;
                            $inscripcion->update();
                            //reversed
                            $order->status = Factura::CANCELADA;
                            $order->payment_status = Factura::PAYMENT_CANCELLED;
                            $order->update();
                            /* FOT TEST*/

                            LogActivity::addToPaymentLog($subjet, $payment, $responsePaymentez);
                            return response()->json(['success' => $subjet], 200);

                        //status != 2
                        } else { return response()->json(['success' => 'transaction_id already received'], 204); }
                    }
                //201	product_id error
                } else { return response()->json(['success' => 'product_id error'], 201); }
                //203	token error
            } else { return response()->json(['success' => 'token error'], 203); }
        }else
            return response()->json(['error' => 'Not CallBack Received'], 400);

    }

    /**
     * Metodo para guardar el callback de la trans en la bbdd
     * @param $data
     * @return Payment
     */
    public static function payment_save($data)
    {
        $payment = new Payment();
        $payment->status = $data['status'];
        $payment->order_description = $data['order_description'];
        $payment->authorization_code = $data['authorization_code'];
        $payment->status_detail = $data['status_detail'];
        $payment->date = $data['date'];
        $payment->message = $data['message'];
        $payment->transaction_id = $data['transaction_id'];
        $payment->dev_reference = $data['dev_reference'];
        $payment->carrier_code = $data['carrier_code'];
        $payment->amount = $data['amount'];
        $payment->paid_date = $data['paid_date'];
        $payment->installments = $data['installments'];
        $payment->application_code = $data['application_code'];
        $payment->stoken = $data['transaction_stoken'];
        //user
        $payment->user_id = $data['user_id'];
        $payment->email = $data['user_email'];
        //card
        $payment->bin = $data['bin'];
        $payment->holder_name = $data['holder_name'];
        $payment->type = $data['type'];
        $payment->number = $data['number'];
        $payment->origin = $data['origin'];
        $payment->save();
        return $payment;
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
            ->where('f.status', Factura::PAGADA)
            ->where('f.payment_status', Factura::PAYMENT_APPROVED)
            ->where('i.inscripcion_type', Inscripcion::INSCRIPCION_ONLINE)
            ->where('i.status', Inscripcion::PAGADA)
            ->where('i.ejercicio_id', $ejercicio->ejercicio_id)
            ->select('f.transaction_id', 'f.id as fId', 'p.date', 'p.paid_date', 'p.amount', 'i.id', 'i.factura_id', 'p.dev_reference', 'p.transaction_id', 'i.user_online', 'f.status', 'f.payment_status', 'i.inscripcion_type', 'i.status', 'i.ejercicio_id')
            ->get();

        return view('inscripcion.online.index-refund', compact('comprobantes'));
    }


    /**
     *  Realizar reembolso
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function setRefund(Request $request)
    {

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


            } else {
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

        $unix_timestamp = (string)Carbon::now()->timestamp;
        $uniq_token_string = $paymentez_server_app_key . '' . $unix_timestamp;

        $uniq_token_hash = hash('sha256', $uniq_token_string, false);

        $string = $paymentez_server_application_code . ';' . $unix_timestamp . ';' . $uniq_token_hash;
        $auth_token = base64_encode($string);

        return $auth_token;
    }

}
