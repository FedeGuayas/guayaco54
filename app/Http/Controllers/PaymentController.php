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
            $transaction_id = $request->transaction->transaction_id;//string
            $dev_reference = $request->transaction->dev_reference;//string
            $carrier_code = $request->transaction->carrier_code;//int
            $amount = $request->transaction->amount;//double
            $paid_date = $request->transaction->paid_date;//string
            $installments = $request->transaction->installments;//int
            $application_code = $request->transaction->application_code;//string

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
            $for_md5 = $request->transaction->id . "_" . $app_code . "_" . $request->user->id . "_" . $app_key;// "123_HF_123456_2GYx7SdjmbucLKE924JVFcmCl8t6nB";
            //$hashed = md5('123_HF_123456_2GYx7SdjmbucLKE924JVFcmCl8t6nB');
            $stoken = md5($for_md5); //hash md5




//            data: {
//                'transaction': {
//                    'status': 1,
//                    'order_description': u'Guayaco Runner 2018',
//                    'authorization_code': u'123456',
//                    'dev_reference': u'1',
//                    'carrier_code': u'00',
//                    'status_detail': 3,
//                    'installments': u'0',
//                    'amount': 8.0,
//                    'paid_date': '19/10/2018 16:41:00',
//                    'date': '19/10/2018 16:41:00',
//                    'message': u'Response by mock',
//                    'stoken': '0622620ac8ec7007258e66e5965354fb',
//                    'id': u'DF-61264',
//                    'application_code': u'FEDE-EC-CLIENT'
//                },
//                'user': {
//                    'id': u'6',
//                    'email': u'osalas@paymentez.com'
//                },
//                'card': {
//                    'bin': u'411111',
//                    'origin': 'Paymentez',
//                    'holder_name': u'Oliver Salas',
//                    'type': 'vi',
//                    'number': u'1111'
//                }
        }


//        $user = User::where('id', 1)->first();
//        LogActivity::addToLog('Respuesta callback paymentez', $user, $data);
//            $notification = [
//                'message_toastr' => ''.$request->all().'',
//                'alert-type' => 'error'];
//            return dd($request->all());


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
