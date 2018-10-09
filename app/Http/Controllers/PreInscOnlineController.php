<?php

namespace App\Http\Controllers;

use App\Asociado;
use App\Categoria;
use App\Configuracion;
use App\Deporte;
use App\Factura;
use App\Inscripcion;
use App\Mpago;
use App\Persona;
use App\Producto;
use App\Talla;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreInscOnlineController extends Controller
{
    /**
     * PreInscOnlineController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return dd('inscripciones del usuario');
    }

    /**
     * Muestra el formulario de Inscripcion Online
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();

        if (!isset($user->persona)) {//no tiene perfil, debe crearlo antes de inscribirse
            $notification = [
                'message_toastr' => 'Debe completar su perfil para poder hacer alguna inscripción',
                'alert-type' => 'error'];
            return redirect()->route('getProfile')->with($notification);
        }

        $data = $request->all();
        $asociado_id = key($data);

        $asociado=Asociado::with('persona')->where('id',$asociado_id)->first();

        //incripcion de asociado
        if (isset($asociado)){

            $edad=$asociado->persona->getEdad();

            $cat_all = Categoria::where('status', Categoria::ACTIVO)
                ->where('categoria', 'NOT LIKE', '%deport%')//en online no se tendra en cuenta la categoria Deportistas
                ->where([
                    ['edad_start', '<=', $edad],
                    ['edad_end', '>=', $edad],
                ])->get();

            $categorias = $cat_all->pluck('categoria', 'id');

            $perfil = $asociado->persona;

        }else{
            //inscripcion del usuario logueado
            $edad = $user->persona->getEdad();

            $cat_all = Categoria::where('status', Categoria::ACTIVO)
                ->where('categoria', 'NOT LIKE', '%deport%')//en online no se tendra en cuenta la categoria Deportistas
                ->where([
                    ['edad_start', '<=', $edad],
                    ['edad_end', '>=', $edad],
                ])->get();

            $categorias = $cat_all->pluck('categoria', 'id');

            $perfil = $user->persona;
        }

        $tallas_all = Talla::where('status', Talla::ACTIVO)
            ->where('stock', '>', 0)
            ->select(DB::raw('concat (talla," - ",upper(color)) as talla,id'))
            ->get();
        $tallas = $tallas_all->pluck('talla', 'id');


        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');



        return view('inscripcion.online.create', compact('categorias', 'tallas', 'perfil', 'formas_pago','asociado'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        $user = $request->user();

        $ahora = Carbon::now();

        $rules = [
            'categoria_id' => 'required',
            'circuito_id' => 'required',
            'talla' => 'required',
            'costo' => 'required',
            'nombres_fact' => 'required',
            'apellidos_fact' => 'required',
            'num_doc_fact' => 'required',
            'email_fact' => 'required|email',
            'telefono_fact' => 'required',
            'direccion_fact' => 'required',
            'mpago' => 'required'
        ];
        $messages = [
            'categoria_id.required' => 'El campo categoría es obligatorio.',
            'circuito_id.required' => 'El campo circuito es obligatorio.',
            'talla.required' => 'El campo talla es obligatorio.',
            'costo.required' => 'El campo costo es obligatorio.',
            'nombres_fact.required' => 'El campo Nombres para Facturación es obligatorio.',
            'apellidos_fact.required' => 'El campo Apellidos para Facturación es obligatorio.',
            'num_doc_fac.required' => 'El campo Identificación para Facturación es obligatorio.',
            'email_fact.required' => 'El campo Email para Facturación es obligatorio. De lo contrario seleccione consumidor final',
            'email_fact.email' => 'El campo Email para Facturación no tiene un formato de correo correcto.',
            'telefono_fact.required' => 'El campo Teléfono para Facturación es obligatorio.',
            'direccion_fact.required' => 'El campo Dirección para Facturación es obligatorio.',
            'mpago.required' => 'El campo método de pago es obligatorio.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput($notification);
        }

        try {

            DB::beginTransaction();

            $persona_id = $user->persona->id; //persona a inscribir es el perfil del usuario logeado
            $asociado_id=$request->input('asociado_id'); //estoy cargando el persona_id
            if (isset($asociado_id)){
                $asociado=Asociado::where('user_id',$user->id)->where('persona_id',$asociado_id)->first();
                if (!isset($asociado)){
                    $notification = [
                        'message_toastr' => "No se encontró el asociado a su cuenta.",
                        'alert-type' => 'error'];
                    return back()->with($notification);
                }else {
                    $persona_id=$asociado_id; //la persona a inscribir seria el perfil del asociado
                }

            }

            $persona = Persona::where('id', $persona_id)->first();

            $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
                ->select('ejercicio_id')
                ->first();


            $inscription_true = Inscripcion::with('producto')
                ->where('persona_id', $persona->id)
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();

            if (count($inscription_true) > 0) {
                $notification = [
                    'message_toastr' => "Ya se encuentra inscrito en " . $inscription_true->producto->circuito->circuito . "/" . $inscription_true->producto->categoria->categoria . ". Solo se puede inscribir una vez",
                    'alert-type' => 'error'];
                return back()->with($notification);
            }

            $categoria_id = $request->input('categoria_id');
            $circuito_id = $request->input('circuito_id');
            $producto = Producto::where('status', Producto::ACTIVO)
                ->where('categoria_id', $categoria_id)
                ->where('circuito_id', $circuito_id)
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();
            $costo = 0;
            if ($producto) {
                $costo = number_format($producto->price, 2, '.', ' ');
            }

            $talla_id = $request->input('talla');
            $talla = Talla::where('id', $talla_id)->where('status', Talla::ACTIVO)->first();

            $mpago_id = $request->input('mpago');
            $mpago = Mpago::where('status', Mpago::ACTIVO)->where('id', $mpago_id)->first();

            //CREAR NUMERO DE FACTURA
            $maxnumFact = DB::table('facturas')->max('numero'); //maximo valor en la columna numero
            if (is_numeric($maxnumFact)) {
                $nextNum = $maxnumFact + 1;
            } else {
                $maxnumFact = 0;
                $nextNum = 1;
            }

            $nombres_fact = $request->input('nombres_fact');
            $apellidos_fact = $request->input('apellidos_fact');
            $email_fact = $request->input('email_fact');
            $direccion_fact = $request->input('direccion_fact');
            $telefono_fact = $request->input('telefono_fact');
            $num_doc_fact = $request->input('num_doc_fact');

            //CREAR FACTURA
            $factura = new  Factura();
            $factura->numero = $nextNum;
            $factura->fecha_edit = $ahora; //inicialmente la fecha de inscripcion
            $factura->subtotal = $producto->price; //El costo normal
            $factura->total = $costo; //el subtotaL -descuento
            $factura->user_id = NULL;
            $factura->persona()->associate($persona); //perfil del cliente a cobrar
            $factura->nombre = $nombres_fact . ' ' . $apellidos_fact;
            $factura->email = $email_fact;
            $factura->direccion = $direccion_fact;
            $factura->telefono = $telefono_fact;
            $factura->identificacion = $num_doc_fact;
            $factura->mpago()->associate($mpago);
            $factura->payment_id = NULL;
            $factura->status = Factura::PENDIENTE;
            $factura->save();

//            //CREAR NUMERO DE CORREDOR solo despues de pagar la inscripcion
//            $maxNumCorr = DB::table('registros')->max('numero'); //maximo valor en la columna numero
//            if (is_numeric($maxNumCorr)) {
//                $nexNumCorredor = $maxNumCorr + 1;
//            } else {
//                $maxNumCorr = 0;
//                $nexNumCorredor = 1;
//            }

            //CREAR INSCRIPCION
            $inscripcion = new Inscripcion();
            $inscripcion->escenario_id = NULL; //la inscripcion es online
            $inscripcion->producto()->associate($producto);
            $inscripcion->persona()->associate($persona); //perfil del inscrito
            $inscripcion->user_id=NULL; //id del empleado, la inscripcion no la hizo un empleado
            $inscripcion->user_edit = NULL;
            $inscripcion->deporte_id = NULL;
            $inscripcion->factura()->associate($factura);
            $inscripcion->fecha =$ahora; //fecha de aprobacion, no se ha aprobado , inicialmente la fecha de inscripcion.
            $inscripcion->num_corredor = NULL; //poner el numero al pagar
            $inscripcion->kit = NULL; //1 cuando sea entregado
            $inscripcion->talla()->associate($talla);
            $inscripcion->costo = $costo; //lo que pagará por la inscripcion
            $inscripcion->ejercicio_id = $ejercicio->ejercicio_id;
            $inscripcion->status = Inscripcion::RESERVADA;
            $inscripcion->inscripcion_type = Inscripcion::INSCRIPCION_ONLINE;
            $inscripcion->user_online = $user->id;//usuario online que hizo la inscripcion
            $inscripcion->save();

            //CREAR EL REGISTRO DEL CORREDOR
//            $registro = new Registro();
//            $registro->numero = $nexNumCorredor;
//            $registro->inscripcion()->associate($inscripcion);
//            $registro->persona()->associate($persona);
//            $registro->save();

            //ACTUALIZAR STOCK DE TALLAS
            $talla->decrement('stock');
            $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
            $talla->update();


            DB::Commit();

            $notification = [
                'message_toastr' => 'Ud se ha inscrito correctamente. Compruebe sus comprobantes en su menú de usuario.',
                'alert-type' => 'success'];
            return redirect()->route('inscription.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
//            $message = $e->getMessage();
           $message = 'Lo sentimos! Ocurrio un error y no se pudo crear la inscripción.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     *   Obtener lo circuitos para la categoria seleccionada
     * En este caso no se tienen en cuenta los deportes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriaCircuito(Request $request)
    {
        if ($request->ajax()) {

            $circuitos = Producto::with('circuito')
                ->where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('id'))
                ->get();

            return response()->json(['data' => $circuitos], 200);
        }
    }



    /**
     * Obtener el costo de la inscripcion
     */
    public function userCosto(Request $request)
    {

        if ($request->ajax()) {

            $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
                ->select('ejercicio_id')
                ->first();

            $producto = Producto::where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('categoria_id'))
                ->where('circuito_id', $request->input('circuito_id'))
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();

            $costo = 0;
            if ($producto) {
                $costo = number_format($producto->price, 2, '.', ' ');
            }

            return response()->json(['data' => $costo], 200);
        }
    }

    /**
     * Mostrar stock de tallas
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTallaStock(Request $request)
    {
        if ($request->ajax()) {

            $talla = Talla::where('status', Talla::ACTIVO)
                ->where('id', $request->input('talla_id'))
                ->first();

            $talla ? $stock = $talla->stock : $stock = 0;

            return response()->json(['data' => $stock], 200);
        }

    }

    /**
     * Obtener todos los comprobantes del usuario
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getComprobantes(Request $request)
    {
        $user=$request->user();

        $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
            ->select('ejercicio_id')
            ->first();

        $comprobantes = Inscripcion::with('producto','factura')
            ->where('user_online', $user->id)
            ->where('ejercicio_id', $ejercicio->ejercicio_id)
            ->get();

        return view('inscripcion.online.index-comprobantes-online',compact('comprobantes'));
    }


}
