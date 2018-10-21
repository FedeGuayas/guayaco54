<?php

namespace App\Http\Controllers;

use App\Configuracion;
use App\Factura;
use App\Inscripcion;
use App\Mail\InscripcionPayOut;
use App\Registro;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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


    public function getCallback(Request $request)
    {
        $configuracion = Configuracion::where('status', Configuracion::ATIVO)->first();

        if ($request->ajax() && $request != null) {

            $responsePaymentez = '';

            $app_code = '';
            $app_key = '';
            $app_code_server = $configuracion->server_app_code;
            $app_key_server = $configuracion->server_app_key;
            $app_code_client = $configuracion->client_app_code;
            $app_key_client = $configuracion->client_app_key;

            //transaction
            $status = $request->transaction->status;//int
            $order_description = $request->transaction->order_description;//string
            $authorization_code = $request->transaction->authorization_code;//string
            $status_detail = $request->transaction->status_detail;//short
            $date = $request->transaction->date;//string
            $message = $request->transaction->message;//string
            $transaction_id = $request->transaction->id;//string
            $dev_reference = $request->transaction->dev_reference;//string,  Identificarás esta compra utilizando esta referencia.
            $carrier_code = $request->transaction->carrier_code;//int
            $amount = $request->transaction->amount;//double
            $paid_date = $request->transaction->paid_date;//string '19/10/2018 16:41:00',
            $installments = $request->transaction->installments;//int
            $application_code = $request->transaction->application_code;//string
            $transaction_stoken = $request->transaction->stoken;//string

            //user
            $user_id = $request->user->id;//string
            $user_email = $request->user->email;//string

            //card
            $bin = $request->card->bin;//string
            $holder_name = $request->card->holder_name;//string
            $type = $request->card->type;//string
            $number = $request->card->number;//string
            $origin = $request->card->origin;//string

            if ($request->transaction->application_code == $app_code_client) {
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
                . " | origin: " . $origin
            ;

            if ($transaction_stoken == $stoken_gen)
            {

                //Buscar en tabla payments la orden de compra por el campo dev_reference
                $order=$resultado;
                if (isset($order)){

                    //compruebe la transaction_id para asegurarse de que no está obteniendo un POST duplicado.
                    $exist_transaction= $buscar_por_transaction_id;
                    //if transaction doesn't exist

                    if ($exist_transaction == null){
                        
                    }
                }

            }



        }


//        $user = User::where('id', 1)->first();
//        LogActivity::addToLog('Respuesta callback paymentez', $user, $data);
//            $notification = [
//                'message_toastr' => ''.$request->all().'',
//                'alert-type' => 'error'];
//            return dd($request->all());


    }

    /**
     * Status de la transaccion
     * @param $status
     * @return string
     */
    //https://paymentez.github.io/api-doc/#status-details
    public function getStatusDescription($status){
        $statusDescription = '';
        //reference on https://paymentez.github.io/api-doc/#status-details
        switch ($status) {
            case 1:
            {
                $statusDescription = "Approved";
                break;
            }
            case 2:
            {
                $statusDescription = "Cancelled";
                break;
            }
            case 4:
            {
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
    public function getStatusDetailDescription($status_detail){
        $statusDetailDescription = '';

        switch ($status_detail)
            {
            case 0:
            {
                $statusDetailDescription = "Waiting for Payment."; break;
            }
            case 1:
            {
                $statusDetailDescription = "Verification required, please see Verification section."; break;
            }
            case 3:
            {
                $statusDetailDescription = "Paid"; break;
            }
            case 6:
            {
                $statusDetailDescription = "Fraud"; break;
            }
            case 7:
            {
                $statusDetailDescription = "Refund"; break;
            }
            case 8:
            {
                $statusDetailDescription = "Chargeback"; break;
            }
            case 9:
            {
                $statusDetailDescription = "Rejected by carrier"; break;
            }
            case 10:
            {
                $statusDetailDescription = "System error"; break;
            }
            case 11:
            {
                $statusDetailDescription = "Paymentez fraud"; break;
            }
            case 12:
            {
                $statusDetailDescription = "Paymentez blacklist"; break;
            }
            case 13:
            {
                $statusDetailDescription = "Time tolerance."; break;
            }
            case 19:
            {
                $statusDetailDescription = "Invalid Authorization Code."; break;
            }
            case 20:
            {
                $statusDetailDescription = "Authorization code expired."; break;
            }
            case 21:
            {
                $statusDetailDescription = "Paymentez Fraud - Pending refund."; break;
            }
            case 22:
            {
                $statusDetailDescription = "Invalid AuthCode - Pending refund."; break;
            }
            case 23:
            {
                $statusDetailDescription = "AuthCode expired - Pending refund."; break;
            }
            case 24:
            {
                $statusDetailDescription = "Paymentez Fraud - Refund requested."; break;
            }
            case 25:
            {
                $statusDetailDescription = "Invalid AuthCode - Refund requested."; break;
            }
            case 26:
            {
                $statusDetailDescription = "AuthCode expired - Refund requested."; break;
            }
            case 27:
            {
                $statusDetailDescription = "Merchant - Pending refund."; break;
            }
            case 28:
            {
                $statusDetailDescription = "Merchant - Refund requested."; break;
            }
            case 30:
            {
                $statusDetailDescription = "Transaction seated (only Datafast)."; break;
            }
            case 31:
            {
                $statusDetailDescription = "Waiting for OTP."; break;
            }
            case 32:
            {
                $statusDetailDescription = "OTP successfully validated."; break;
            }
            case 33:
            {
                $statusDetailDescription = "OTP not validated."; break;
            }
            case 34:
            {
                $statusDetailDescription = "Partial refund"; break;
            }

        }

            return $statusDetailDescription;
        }



    /**
     * Actualizar estado de inscripcion, crear registro de corredor y enviar correo de pago al usuario
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public
    function sendInscripcionPayOut(Request $request)
    {
        if ($request->ajax()) {

            $transaction_id = $request->input('payID');
            $insc_id = $request->input('insc_id');

            $inscripcion = Inscripcion::with('factura', 'user', 'producto', 'persona')->where('id', $insc_id)->first();
            $factura = $inscripcion->factura;

            if (!$inscripcion || !$factura) {
                $notification = 'Se realizo su pago pero ocurrio un error al actualizar el estado de la inscripción, por favor pongase en contacto con nosotros proporcionando este ID de transacción ID: ' . $transaction_id . '';
                return response()->json(['data' => $notification], 400);
            }

            $factura->payment_id = $transaction_id;
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

                $inscripcion->status = Inscripcion::PAGADA;
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

                //email de confirmacion de compra al correo de facturacion del usuario
                Mail::to($factura->email)->send(new InscripcionPayOut($inscripcion));

                if (count(Mail::failures() < 0)) {
                    //correo enviado
                    LogActivity::addToLog('Enviado correo de confirmacion de pago (inscripcion->id , factura_email) ', $request->user(), $inscripcion->id, $factura->email);
                } else {
                    //correo no enviado
                    LogActivity::addToLog('Error al enviar correo de confirmacion de pago (inscripcion->id , factura_email) ', $request->user(), $inscripcion->id, $factura->email);
                }

                DB::commit();

                $message = 'Se actualizó el estado del pago';
                return response()->json(['data' => $message], 200);

            } catch (\Exception $e) {
                DB::rollback();
//              $message=$e->getMessage();
                $message = 'Ocurrio un error y no se pudo actualizar la inscripción';
                return response()->json(['data' => $message], 400);

            }

        }
    }


    /**
     *  Realizar reembolso
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public
    function setRefund(Request $request)
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

}
