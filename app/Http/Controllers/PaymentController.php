<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Inscripcion;
use App\Mail\InscripcionPayOut;
use App\Registro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Facades\App\Classes\LogActivity;

class PaymentController extends Controller
{

    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * Actualizar estado de inscripcion, crear registro de corredor y enviar correo de pago al usuario
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendInscripcionPayOut(Request $request)
    {
        if ($request->ajax()) {

            try {

                DB::beginTransaction();

                $transaction_id = $request->input('payID');
                $insc_id=$request->input('insc_id');

                $inscripcion = Inscripcion::with('factura', 'user','producto','persona')->where('id', $insc_id)->first();
                $factura = $inscripcion->factura;

                if (!$inscripcion || !$factura) {
                    $notification = 'Se realizo su pago pero ocurrio un error al actualizar el estado de la inscripción, por favor pongase en contacto con nosotros proporcionando este ID de transacción ID: ' . $transaction_id . '';
                    return response()->json(['data' => $notification], 400);
                }

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

                $factura->status = Factura::PAGADA;
                $factura->payment_id=$transaction_id;
                $factura->update();

                $persona = $inscripcion->persona;

                //CREAR EL REGISTRO DEL CORREDOR
                $registro = new Registro();
                $registro->numero = $nexNumCorredor;
                $registro->inscripcion()->associate($inscripcion);
                $registro->persona()->associate($persona);
                $registro->save();


                Mail::to($factura->email)->send(new InscripcionPayOut($inscripcion));

                if (count(Mail::failures() < 0)) {
                    //correo enviado
                    LogActivity::addToLog('Enviado correo de confirmacion de pago (inscripcion->id , factura_email) ', $request->user(), $inscripcion->id,$factura->email);
                } else {
                    //correo no enviado
                    LogActivity::addToLog('Error al enviar correo de confirmacion de pago (inscripcion->id , factura_email) ', $request->user(), $inscripcion->id,$factura->email);
                }

                DB::commit();

                $notification = [
                    'message_toastr' => 'Reserva de Inscripción confirmada correctamente.',
                    'alert-type' => 'success'];
                return response()->json(['data' => $notification], 400);

            } catch (\Exception $e) { //en caso de error viro al estado anterior
                DB::rollback();
//              $message=$e->getMessage();
                $message = 'Ocurrio un error y no se pudo aprobar la reserva';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);
            }

        }
    }

}
