<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Circuito;
use App\Configuracion;
use App\Deporte;
use App\Descuento;
use App\Factura;
use App\Inscripcion;
use App\Mpago;
use App\Persona;
use App\Producto;
use App\Registro;
use App\Talla;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Facades\App\Classes\LogActivity;
use Barryvdh\DomPDF\Facade as PDF;

class InscripcionController extends Controller
{
    /**
     * InscripcionController constructor.
     */
    public function __construct()
    {
        setlocale(LC_TIME, 'es_ES.utf8');
        $this->middleware('auth');
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('inscripcion.interna.index');
    }

    /**
     * Inscripciones datatable ajax
     */
    public function getAll(Request $request)
    {

        $user = $request->user();

        if ($user->can('view_inscripciones')) {

            if ($request->ajax()) {

                $inscripcion = Inscripcion::

                with('user', 'producto', 'producto.categoria', 'producto.circuito', 'persona', 'talla', 'factura')
                    ->join('personas', 'personas.id', '=', 'inscripcions.persona_id')
                    ->leftJoin('registros', 'registros.inscripcion_id', '=', 'inscripcions.id')
                    ->where('inscripcions.status', '=', Inscripcion::PAGADA)//solo mostrar las pagadas
                    ->orderBy('inscripcions.id', 'desc')
//                ->whereHas('roles', function($q){ //con rol=employee
//                    $q->where('name', '=', 'employee');
//                })
//                ->whereDoesntHave('roles', function($query) { //que el rol no sea employee
//                    $query->where('name', '=', 'employee');
//                })
                    ->
                    select('inscripcions.*', 'registros.inscripcion_id', 'registros.numero');

                $action_buttons = '
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                <div class="dropdown-menu dropdown-menu-left">
                 @can(\'view_comprobantes\')
                    <a class="dropdown-item" href="{{route(\'admin.inscripcion.recibo\',[$id])}}" data-toggle="tooltip" data-placement="top" title="Imprimir Recibo" target="_blank">
                        <i class="fa fa-file-pdf-o text-danger"></i> Imprimir
                    </a>
                @endcan
                @can(\'edit_inscripciones\')
                    @if ($inscripcion_type==\App\Inscripcion::INSCRIPCION_PRESENCIAL)
                    <a class="dropdown-item" href="{{ route(\'inscriptions.edit\',[$id]) }}" data-toggle="tooltip" data-placement="top" title="Editar Inscripción">
                        <i class="fa fa-pencil text-success"></i> Editar
                    </a>
                    @endif
                @endcan
                @can(\'delete_inscripciones\')
                   @if ($inscripcion_type==\App\Inscripcion::INSCRIPCION_PRESENCIAL)
                    <a class="dropdown-item delete" href="#" data-id="{{$id}}" data-toggle="tooltip" data-placement="top" title="Eliminar">
                        <i class="fa fa-trash-o text-danger"></i>  Eliminar
                    </a>
                    @endif
                @endcan
                @if ($deporte_id==\'\')  
                    @if ($kit!=\\App\\Inscripcion::KIT_ENTREGADO)   
                        @can(\'entregar_kit\')
                        <a class="dropdown-item status_kit" href="#" data-id="{{$id}}" data-toggle="tooltip" data-placement="top" title="Entregar Kit">
                            <i class="fa fa-thumbs-o-up fa-2x text-primary"></i> Entregar
                        </a>
                        @endcan
                    @else
                        @can(\'devolver_kit\')
                        <a class="dropdown-item status_kit" href="#" data-id="{{$id}}" data-toggle="tooltip" data-placement="top" title="Devolver Kit">
                            <i class="fa fa-thumbs-o-down fa-2x text-danger"></i> Devolver
                        </a>
                        @endcan
                    @endif
                @endif
                </div>
            </div>
                ';

                $datatable = Datatables::of($inscripcion)
                    ->addColumn('actions', $action_buttons)
                    ->addColumn('nombres', function ($inscripcion) {
                        return $inscripcion->persona->getFullName();
                    })
                    ->filterColumn('nombres', function ($query, $keyword) {
                        $query->whereRaw("CONCAT(personas.nombres,' ',personas.apellidos) like ?", ["%{$keyword}%"]);
                    })
                    ->addColumn('numero', function ($inscripcion) {
                        return $inscripcion->numero;
                    })
                    ->filterColumn('numero', function ($query, $keyword) {
                        $query->whereRaw("registros.numero = ?", ["{$keyword}"]);
                    })
                    ->addColumn('tallas', function ($inscripcion) {
                        if ($inscripcion->talla) {
                            return $inscripcion->talla->talla . '/' . $inscripcion->talla->color;
                        }
                    })
                    ->rawColumns(['actions'])
                    ->setRowId('id');
                //Agregar variables a a la respuesta json del datatables
                if ($request->draw == 1) {
                    $categorias = \App\Categoria::distinct('categoria')->pluck('categoria');
                    $circuitos = \App\Circuito::distinct('circuito')->pluck('circuito');
                    $datatable->with([
                        'allCategorias' => $categorias,
                        'allCircuitos' => $circuitos
                    ]);
                }

                return $datatable->make(true);

            }

        } else abort(403);

    }


    /**
     * Muestra el formulario para incripcion a un cliente
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Persona $persona)
    {
        $persona_email = $persona->email;

        $persona_email == '' || is_null($persona_email) ? $error_email = true : $error_email = false;

        //verificar si se encuentra inscrito en el año
        $config = Configuracion::with('ejercicio', 'impuesto')->where('status', Configuracion::ATIVO)->first();
        $ejercicio = $config->ejercicio_id;
        $inscription_true = Inscripcion::with('producto')
            ->where('persona_id', $persona->id)
            ->where('ejercicio_id', $ejercicio)
            ->first();

        if (count($inscription_true) > 0) {

            $notification = [
                'message_toastr' => "El cliente ya se encuentra inscrito en " . $inscription_true->producto->circuito->circuito . "/" . $inscription_true->producto->categoria->categoria . " en la presente temporada de Guayaco Runner",
                'alert-type' => 'error'];
            return back()->with($notification);
        }

        $edad = $persona->getEdad();

        $cat_all = Categoria::where('status', Categoria::ACTIVO)
            ->where([
                ['edad_start', '<=', $edad],
                ['edad_end', '>=', $edad],
            ])->get();

        $categorias = $cat_all->pluck('categoria', 'id');

        $tallas_all = Talla::where('status', Talla::ACTIVO)
            ->where('stock', '>', 0)
            ->select(DB::raw('concat (talla," - ",color) as talla,id'))
            ->get();
        $tallas = $tallas_all->pluck('talla', 'id');

        $deporte_all = Deporte::where('status', Deporte::ACTIVO)->get();
        $deportes = $deporte_all->pluck('deporte', 'id');

        $descuentos_all = Descuento::where('status', Descuento::ACTIVO)
            ->select(DB::raw('concat (porciento," % ",nombre) as nombre,id'))
            ->get();
        $descuentos = $descuentos_all->pluck('nombre', 'id');

        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');

        return view('inscripcion.interna.create', compact('categorias', 'tallas', 'deportes', 'persona', 'formas_pago', 'descuentos', 'error_email'));
    }


    /**Guaradar Inscripcion BACKEND
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = $request->user();

        $ahora = Carbon::now();

        $rules = [
            'persona_id' => 'required',
            'categoria_id' => 'required',
            'circuito_id' => 'required',
            'talla' => 'required_without:deporte_id',
            'mpago' => 'required',
            'costo' => 'required',
            'nombres_fact' => 'required',
            'apellidos_fact' => 'required',
            'num_doc_fact' => 'required',
            'email_fact' => 'required|email',
            'telefono_fact' => 'required',
            'direccion_fact' => 'required'
        ];
//
        $messages = [
            'persona_id.required' => 'Perfil de persona a inscribir no encontrado.',
            'categoria_id.required' => 'El campo categoría es obligatorio.',
            'circuito_id.required' => 'El campo circuito es obligatorio.',
            'talla.required' => 'El campo talla es obligatorio cuando deportes no está presente.',
            'mpago.required' => 'El campo método de pago es obligatorio.',
            'costo.required' => 'El campo costo es obligatorio.',
            'nombres_fact.required' => 'El campo Nombres para Facturación es obligatorio.',
            'apellidos_fact.required' => 'El campo Apellidos para Facturación es obligatorio.',
            'num_doc_fac.required' => 'El campo Identificación para Facturación es obligatorio.',
            'email_fact.required' => 'El campo Email para Facturación es obligatorio. De lo contrario seleccione consumidor final',
            'email_fact.email' => 'El campo Email para Facturación no tiene un formato de correo correcto.',
            'telefono_fact.required' => 'El campo Teléfono para Facturación es obligatorio.',
            'direccion_fact.required' => 'El campo Dirección para Facturación es obligatorio.'
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


            $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
                ->select('ejercicio_id')
                ->first();

            $persona_id = $request->input('persona_id');
            $persona = Persona::where('id', $persona_id)->first();

            $categoria_id = $request->input('categoria_id');
            $circuito_id = $request->input('circuito_id');
            $producto = Producto::where('categoria_id', $categoria_id)
                ->where('circuito_id', $circuito_id)
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();

            $talla_id = $request->input('talla');
            $talla = Talla::where('id', $talla_id)->where('status', Talla::ACTIVO)->first();

            $mpago_id = $request->input('mpago');
            $mpago = Mpago::where('status', Mpago::ACTIVO)->where('id', $mpago_id)->first();

            //con descuento aplicado si lo hay
            $costo = number_format($request->input('costo'), 2, '.', '');
            $descuento = Descuento::where('status', Descuento::ACTIVO)
                ->where('id', $request->input('descuentos'))
                ->first();
            $desc = 0; //descuento aplicado
            if ($descuento || $costo !== $producto->price) {
                $desc = ($producto->price) - ($costo);
            }

            $deporte = Deporte::where('id', $request->input('deporte_id'))->where('status', Deporte::ACTIVO)->first();

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

            if (!isset($deporte)) { //la factura se generará solo si no es deportista
                //CREAR FACTURA
                $factura = new  Factura();
                $factura->numero = $nextNum;
                $factura->fecha_edit = $ahora; //Activa, cancelada
                $factura->descuento = $desc; //descuento que se hizo
                $factura->subtotal = $producto->price; //El costo normal
                $factura->total = $costo; //el subtotaL -descuento
                $factura->user_id = $user->id;
                $factura->persona()->associate($persona);
                if ($descuento) {
                    $factura->descuento()->associate($descuento);
                }
                $factura->nombre = $nombres_fact . ' ' . $apellidos_fact;
                $factura->email = $email_fact;
                $factura->direccion = $direccion_fact;
                $factura->telefono = $telefono_fact;
                $factura->identificacion = $num_doc_fact;
                $factura->mpago()->associate($mpago);
                $factura->transaction_id = NULL;
                $factura->status = Factura::PAGADA;  //crear registro (numero de corredor)
                $factura->save();
            }

            //CREAR NUMERO DE CORREDOR
            $maxNumCorr = DB::table('registros')->max('numero'); //maximo valor en la columna numero
            if (is_numeric($maxNumCorr)) {
                $nexNumCorredor = $maxNumCorr + 1;
            } else {
                $maxNumCorr = 0;
                $nexNumCorredor = 1;
            }

            //CREAR INSCRIPCION
            $inscripcion = new Inscripcion();
            $inscripcion->escenario_id = $user->escenario_id;
            $inscripcion->producto()->associate($producto);
            $inscripcion->persona()->associate($persona);
            $inscripcion->user()->associate($user);
            $inscripcion->user_edit = NULL;
            $deporte ? $inscripcion->deporte_id = $deporte->id : $inscripcion->deporte_id = NULL;
            $deporte ? $inscripcion->factura_id = NULL : $inscripcion->factura()->associate($factura);
            $inscripcion->fecha = $ahora; //fecha de aprobacion,
            $inscripcion->num_corredor = $nexNumCorredor;
            $inscripcion->kit = NULL;
            if (!isset($deporte) && isset($talla)) { //no es deportista y se escogio la talla
                $inscripcion->talla()->associate($talla);
            } else { //es deportista y no se escogio la talla
                $inscripcion->talla_id = NULL;
            }
            $inscripcion->costo = $costo;
            $inscripcion->ejercicio_id = $ejercicio->ejercicio_id;
            $inscripcion->status = Inscripcion::PAGADA;
            $inscripcion->inscripcion_type = Inscripcion::INSCRIPCION_PRESENCIAL;
            $inscripcion->save();

            //CREAR EL REGISTRO DEL CORREDOR
            $registro = new Registro();
            $registro->numero = $nexNumCorredor;
            $registro->inscripcion()->associate($inscripcion);
            $registro->persona()->associate($persona);
            $registro->save();

            //ACTUALIZAR STOCK DE TALLAS
            if (!isset($deporte) && isset($talla)) { //no es deportista y se escogio la talla
                $talla->decrement('stock');
                $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
                $talla->update();
            }


            DB::Commit();

            $notification = [
                'message_toastr' => 'Inscripción creada correctamente. Debe imprimir el comprobante de pago con el cuál se podrá retirar el Kit.',
                'alert-type' => 'success'];
            return redirect()->route('inscriptions.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
//            $e->getCode() == '23000' ? $message = 'El cliente ya se encuentra inscrito en la carrera' : $message = 'Lo sentimos! Ocurrio un error y no se pudo crear la inscripción.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Inscripcion $inscripcion)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Inscripcion $inscription)
    {
        $edad = $inscription->persona->getEdad();

        $cat_all = Categoria::where('status', Categoria::ACTIVO)
            ->where([
                ['edad_start', '<=', $edad],
                ['edad_end', '>=', $edad],
            ])->get();

        $categorias = $cat_all->pluck('categoria', 'id');

        $circuito = Circuito::where('id', $inscription->producto->circuito_id)->get();
        $circuito_set = $circuito->pluck('circuito', 'id');

        $tallas_all = Talla::where('stock', '>=', 0)
            ->select(DB::raw('concat (talla," - ",color) as talla,id'))
            ->get();
        $tallas = $tallas_all->pluck('talla', 'id');

        $talla_agotada = false;
        if ($inscription->talla) {
            $talla_de_inscripcion = Talla::where('id', $inscription->talla_id)->select('stock')->first();
            $talla_de_inscripcion->stock > 0 ? $talla_agotada = false : $talla_agotada = true;
        }

        $deporte_all = Deporte::where('status', Deporte::ACTIVO)->get();
        $deportes = $deporte_all->pluck('deporte', 'id');

        $descuentos_all = Descuento::where('status', Descuento::ACTIVO)
            ->select(DB::raw('concat (porciento," % ",nombre) as nombre,id'))
            ->get();
        $descuentos = $descuentos_all->pluck('nombre', 'id');

        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');

        return view('inscripcion.interna.edit', compact('categorias', 'tallas', 'deportes', 'inscription', 'formas_pago', 'descuentos', 'circuito_set', 'talla_agotada'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscripcion $inscription)
    {
        $user = $request->user(); //usuario logueado que edita

        $ahora = Carbon::now();

        $rules = [
            'categoria_id' => 'required',
            'circuito_id' => 'required',
            'talla' => 'required_without:deporte_id',
            'mpago' => 'required',
            'costo' => 'required',
            'nombres_fact' => 'required',
            'num_doc_fact' => 'required',
            'email_fact' => 'required|email',
            'telefono_fact' => 'required',
            'direccion_fact' => 'required'
        ];
//
        $messages = [
            'categoria_id.required' => 'El campo categoría es obligatorio.',
            'circuito_id.required' => 'El campo circuito es obligatorio.',
            'talla.required' => 'El campo talla es obligatorio cuando deportes no está presente.',
            'mpago.required' => 'El campo método de pago es obligatorio.',
            'costo.required' => 'El campo costo es obligatorio.',
            'nombres_fact.required' => 'El campo Nombres para Facturación es obligatorio.',
            'num_doc_fac.required' => 'El campo Identificación para Facturación es obligatorio.',
            'email_fact.required' => 'El campo Email para Facturación es obligatorio. De lo contrario seleccione consumidor final',
            'email_fact.email' => 'El campo Email para Facturación no tiene un formato de correo correcto.',
            'telefono_fact.required' => 'El campo Teléfono para Facturación es obligatorio.',
            'direccion_fact.required' => 'El campo Dirección para Facturación es obligatorio.'
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

            $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
                ->select('ejercicio_id')
                ->first();

            $categoria_id = $request->input('categoria_id');
            $circuito_id = $request->input('circuito_id');
            $producto = Producto::where('categoria_id', $categoria_id)
                ->where('circuito_id', $circuito_id)
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();

            $talla_id = $request->input('talla');
            $talla = Talla::where('id', $talla_id)->first();

            $mpago_id = $request->input('mpago');
            $mpago = Mpago::where('status', Mpago::ACTIVO)->where('id', $mpago_id)->first();

            $persona_id = $inscription->persona_id;
            $persona = Persona::where('id', $persona_id)->first();

            //con descuento aplicado si lo hay
            $costo = number_format($request->input('costo'), 2, '.', '');
            $descuento = Descuento::where('status', Descuento::ACTIVO)
                ->where('id', $request->input('descuentos'))
                ->first();
            $desc = 0; //descuento aplicado
            if ($descuento || $costo !== $producto->price) {
                $desc = ($producto->price) - ($costo);
            }

            $deporte = Deporte::where('id', $request->input('deporte_id'))->where('status', Deporte::ACTIVO)->first();

            $nombres_fact = $request->input('nombres_fact');
            $apellidos_fact = $request->input('apellidos_fact');
            $email_fact = $request->input('email_fact');
            $direccion_fact = $request->input('direccion_fact');
            $telefono_fact = $request->input('telefono_fact');
            $num_doc_fact = $request->input('num_doc_fact');

            //Ahora no se ecogio deporte pero anteriormente (era deportista y no tenia factura)
            if (!isset($deporte) && ($inscription->deporte_id && !$inscription->factura_id)) { //CAMBIO DE INSCRIPCION DE DEPORTISTA A CORREDOR NORMAL
                //CREAR NUMERO DE FACTURA
                $maxnumFact = DB::table('facturas')->max('numero'); //maximo valor en la columna numero
                if (is_numeric($maxnumFact)) {
                    $nextNum = $maxnumFact + 1;
                } else {
                    $maxnumFact = 0;
                    $nextNum = 1;
                }
                //CRER NUEVA FACTURA
                $factura = new  Factura();
                $factura->numero = $nextNum;
                $factura->fecha_edit = $ahora; //Inicialmente es la misma que la de creacion
                $factura->descuento = $desc; //descuento que se hizo
                $factura->subtotal = $producto->price; //El costo normal
                $factura->total = $costo; //el subtotaL -descuento
                $factura->user_id = $user->id; //usuario que la crea
                $factura->persona()->associate($persona);
                if ($descuento) {
                    $factura->descuento()->associate($descuento);
                }
                $factura->nombre = $nombres_fact . ' ' . $apellidos_fact;
                $factura->email = $email_fact;
                $factura->direccion = $direccion_fact;
                $factura->telefono = $telefono_fact;
                $factura->identificacion = $num_doc_fact;
                $factura->mpago()->associate($mpago);
                $factura->transaction_id = NULL; //solo para pago con tarjeta online
                $factura->status = Factura::PAGADA;
                $factura->save();
                //EDITAR INSCRIPCION
                $inscription->producto()->associate($producto);
                $inscription->user_edit = $user->id;
                $inscription->deporte_id = NULL; //no se escogio deporte
                $inscription->factura()->associate($factura); //se genero factura nueva
                if (!isset($deporte) && isset($talla)) { //no es deportista y se escogio la talla
                    $inscription->talla()->associate($talla);
                }
                $inscription->costo = $costo;
                $inscription->update();
                //ACTUALIZAR STOCK DE TALLAS
                $talla->decrement('stock');
                $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
                $talla->update();


            } else { //CAMBIO EN INSCRIPCION DE CORREDOR NORMAL o Actualizacion de deportistas

                //Validar que existe la factura, porque los deportistas no tienen factura en tonces daria error  $inscripcion->factura_id
                if ($inscription->factura) {
                    $factura = Factura::where('id', $inscription->factura_id)->first();
                    $old_costo = $factura->total;
                }


                //No se escogio deporte y tenia factura
                if (!isset($deporte) && $inscription->factura_id) {//Cambio de Corredor normal a normal

                    //la factura se actualizara solo sino es DEPORTISTA
                    $factura->fecha_edit = $ahora; //fecha en que se edita
                    $factura->descuento = $desc; //descuento que se hizo
                    $factura->subtotal = $producto->price; //El costo normal
                    $factura->total = $costo; //el subtotal - descuento
                    if ($descuento) {
                        $factura->descuento()->associate($descuento);
                    }
                    $factura->nombre = $nombres_fact . ' ' . $apellidos_fact;
                    $factura->email = $email_fact;
                    $factura->direccion = $direccion_fact;
                    $factura->telefono = $telefono_fact;
                    $factura->identificacion = $num_doc_fact;
                    $factura->mpago()->associate($mpago);
                    $factura->update();
                    LogActivity::addToLog('Factura editada por trabajador (Valores de Factura)', $user, $old_costo, $factura->costo);
                    //ACTUALIZAR STOCK DE TALLAS
                    //se cambio de talla
                    if (isset($talla) && ($talla->id != $inscription->talla_id)) {
                        //incrementar la talla anterior
                        $talla_anterior = Talla::where('id', $inscription->talla_id)->first();
                        $talla_anterior->increment('stock');
                        $talla_anterior->stock > 0 ? $talla_anterior->status = Talla::ACTIVO : $talla_anterior->status = Talla::INACTIVO;
                        $talla_anterior->update();
                        //decrementrar talla actual
                        $talla->decrement('stock');
                        $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
                        $talla->update();
                    }

                    //EDITAR INSCRIPCION
                    $inscription->producto()->associate($producto);
                    $inscription->user_edit = $user->id; //usuario que edita
                    $inscription->deporte_id = NULL;
                    //si se cambia de talla
                    if (isset($talla) && ($talla->id != $inscription->talla_id)) {
                        $inscription->talla()->associate($talla);
                    }
                    $inscription->costo = $costo;
                    $inscription->update();

                    //se cambio de corredor normal a deportista
                } elseif (isset($deporte) && $inscription->factura_id) { //Se escogio deporte y anteriormente tenia factura
                    //se cancela la factura,
                    $factura->status = Factura::CANCELADA;
                    $factura->update();
                    LogActivity::addToLog('Factura cancelada, cambio de corredor a deportista (Valores de la Factura)', $user, $old_costo, '0');
                    //ACTUALIZAR STOCK DE TALLAS
                    //se cambio de talla, se deselecciono la anterior al cambiarse a deportista
                    if (!isset($talla)) {
                        //incrementar la talla anterior
                        $talla_anterior = Talla::where('id', $inscription->talla_id)->first();
                        $talla_anterior->increment('stock');
                        $talla_anterior->stock > 0 ? $talla_anterior->status = Talla::ACTIVO : $talla_anterior->status = Talla::INACTIVO;
                        $talla_anterior->update();
                    }
                    //EDITAR INSCRIPCION
                    $inscription->producto()->associate($producto);
                    $inscription->user_edit = $user->id; //usuario que edita
                    $inscription->deporte_id = $deporte->id;
                    $inscription->factura_id = NULL; //se anula la factura
                    //deportista, no hay talla
                    $inscription->talla_id = NULL;
                    $inscription->costo = $costo;
                    $inscription->update();

                    //Era deportistas y sigue siendolo
                } elseif (isset($deporte) && !$inscription->factura_id) { //Se escogio deporte y anterirormente no tenia factura

                    //EDITAR INSCRIPCION
                    $inscription->producto()->associate($producto);
                    $inscription->user_edit = $user->id; //usuario que edita
                    $inscription->deporte_id = $deporte->id;
                    $inscription->update();
                    LogActivity::addToLog('Actualizacion de inscripcion, deportista', $user);
                }
            }

            DB::Commit();

            $notification = [
                'message_toastr' => 'Inscripción actualizada correctamente.',
                'alert-type' => 'success'];
            return redirect()->route('inscriptions.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
//            $e->getCode() == '23000' ? $message = 'El cliente ya se encuentra inscrito en la carrera' : $message = 'Lo sentimos! Ocurrio un error y no se pudo crear la inscripción.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

    }

    /**
     * Eliminar inscripcion, La factura se cancela
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Inscripcion $inscription)
    {
        if ($request->user()->can('delete_inscripciones') && $request->ajax()) {

            try {

                DB::beginTransaction();

                if (isset($inscription->talla)) {

                    $talla = $inscription->talla;
                    $talla->increment('stock');
                    $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
                    $talla->update();
                }

                $registro = Registro::where('inscripcion_id', $inscription->id)->first();
                $registro ? $registro->delete() : $registro = false;
                LogActivity::addToLog('Registro eliminado (No. inscripcion, No. corredor) ', $request->user(), $inscription->id, $inscription->num_corredor);

                $factura = $inscription->factura;
                if ($factura) {
                    $factura->status = Factura::CANCELADA;
                    $factura->update();
                    LogActivity::addToLog('Inscripcion eliminada, Factura cancelada (Inscripcion->id, Factura->id) ', $request->user(), $inscription->id, $factura->id);
                }

                $inscription->delete();

                DB::Commit();

                return response()->json(['data' => $inscription], 200);

            } catch (\Exception $e) {
                DB:: rollBack();
                return response()->json(['data' => $inscription, 404]);

            }
        } else  return response()->json(['data' => $inscription], 403);
    }

    /**
     * Cambia estado de kit a entregado=1
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setKit(Inscripcion $inscripcion)
    {
        $inscripcion->kit == Inscripcion::KIT_POR_ENTREGAR ? $inscripcion->kit = Inscripcion::KIT_ENTREGADO : $inscripcion->kit = Inscripcion::KIT_POR_ENTREGAR;
        $inscripcion->update();
        return response()->json(['data' => $inscripcion], 200);
    }

    /**Para Backend
     *
     * Obtener lo circuitos para la categoia seleccionada
     */
    public function getCatCir(Request $request)
    {
        if ($request->ajax()) {

            $circuitos = Producto::with('circuito')
                ->where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('id'))
                ->get();

            $categoria = Categoria::where('id', $request->input('id'))->first();

            $deportista = false;
            if (stristr($categoria->categoria, 'deport')) {
                $deportista = true;
            }
            return response()->json(['data' => $circuitos, 'deportista' => $deportista], 200);
        }
    }

    /**BACKEND
     * Obtener el costo de la inscripcion,  tener en cuenta los descuentos
     */
    public function getCosto(Request $request)
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


            //edad de persona a inscribir
            $edad = intval($request->input('persona_edad'));
            if ($edad >= 65) { //adulto mayor 50% descuento
                $descuento = $costo * 0.50;
                $costo = $costo - $descuento;
                $costo = number_format($costo, 2, '.', ' ');
                return response()->json(['data' => $costo], 200);
            }

            $descuento = Descuento::where('status', Descuento::ACTIVO)
                ->where('id', $request->input('descuento_id'))
                ->first();

            if ($descuento) { //descuento seleccionado
                $descuento = $descuento->appDescuento($costo);
                $costo = $costo - $descuento;
                $costo = number_format($costo, 2, '.', ' ');
                return response()->json(['data' => $costo], 200);
            }

            return response()->json(['data' => $costo], 200);

        }
    }


    /**BACK
     * Imprimir Registro de Inscripción (Inscripcion ya pagada y aprobada)
     * @param Factura $factura
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reciboInscripcion(Inscripcion $inscripcion)
    {
        setlocale(LC_ALL, 'es');
        $pdf = PDF::loadView('inscripcion.interna.recibo', compact('inscripcion'));
        return $pdf->stream('Recibo de Inscripcion No-' . $inscripcion->id . '.pdf');

    }


    /////////////////////////Reservas////////////////////////////////////

    /**
     * Muestra las inscripciones pendientes de pago, Western Union, Inscripcion Online (Contado)
     * @return mixed
     */
    public function reservas()
    {
        Carbon::setLocale(config('app.locale'));
        $inscripciones = Inscripcion::
        with('user', 'producto', 'persona', 'talla', 'factura')
            ->where('inscripcions.status', '=', Inscripcion::RESERVADA)
            ->get();

        return view('inscripcion.reservas.reservas', ['inscripciones' => $inscripciones]);
    }


    /**
     * Cancelar reserva
     * @return mixed
     */
    public function reservaCancel(Request $request, $id)
    {
        if ($request->user()->can('delete_reservas')) {

            try {

                DB::beginTransaction();

                $inscripcion = Inscripcion::where('id', $id)->with('factura', 'user', 'talla')->first();

                //actualizar el stock y status de la talla
                $talla = $inscripcion->talla;
                $talla->increment('stock');
                $talla->stock > 0 ? $talla->status = Talla::ACTIVO : $talla->status = Talla::INACTIVO;
                $talla->update();

                $registro = Registro::where('inscripcion_id', $inscripcion->id)->first();
                $registro ? $registro->delete() : $registro = false;
                LogActivity::addToLog('Reserva eliminada, Registro eliminado (No. inscripcion, No. corredor) ', $request->user(), $inscripcion->id, $inscripcion->num_corredor);

                $factura = $inscripcion->factura;
                if ($factura) {
                    $factura->status = Factura::CANCELADA;
                    $factura->update();
                    LogActivity::addToLog('Reserva eliminada, Factura cancelada (Inscripcion->id, Factura->id) ', $request->user(), $inscripcion->id, $factura->id);
                }

                $inscripcion->delete();

                DB::commit();

                $notification = [
                    'message_toastr' => 'Reserva eliminada correctamente.',
                    'alert-type' => 'success'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);

            } catch (\Exception $e) { //en caso de error viro al estado anterior
                DB::rollback();
//                $message=$e->getMessage();
                $message = 'Ocurrio un error al cancelar la reserva';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);
            }

        } else return abort(403);

    }

    /**
     * Confirmar reserva
     * @param $id
     * @return mixed
     */
    public function reservaConfirm(Request $request, $id)
    {
        if ($request->user()->can('add_reservas')) {

            try {

                DB::beginTransaction();
                $user = $request->user();

                //CREAR NUMERO DE CORREDOR
                $maxNumCorr = DB::table('registros')->max('numero'); //maximo valor en la columna numero
                if (is_numeric($maxNumCorr)) {
                    $nexNumCorredor = $maxNumCorr + 1;
                } else {
                    $maxNumCorr = 0;
                    $nexNumCorredor = 1;
                }

                $inscripcion = Inscripcion::where('id', $id)->with('factura', 'user')->first();
                $inscripcion->status = Inscripcion::PAGADA;
                $inscripcion->num_corredor = $nexNumCorredor;
                $inscripcion->inscripcion_type=Inscripcion::INSCRIPCION_ONLINE;
                $inscripcion->user()->associate($user); //usuario que confirmo la reserva, quien la aprobo o cobro aunque sea online
                $inscripcion->fecha = Carbon::now(); //fecha de aprobada
                $inscripcion->escenario_id = $user->escenario_id; //escenario donde se confirmo la reserva
                $inscripcion->update();

                $factura = Factura::where('id', $inscripcion->factura_id)->first();
                $factura->status = Factura::PAGADA;
                $factura->fecha_edit = Carbon::now();
                $factura->update();

                $persona=$inscripcion->persona;

                //CREAR EL REGISTRO DEL CORREDOR
                $registro = new Registro();
                $registro->numero = $nexNumCorredor;
                $registro->inscripcion()->associate($inscripcion);
                $registro->persona()->associate($persona);
                $registro->save();

                LogActivity::addToLog('Reserva confirmada,(inscripcion->id) ', $request->user(), $inscripcion->id);

                DB::commit();

                $notification = [
                    'message_toastr' => 'Reserva de Inscripción confirmada correctamente.',
                    'alert-type' => 'success'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);

            } catch (\Exception $e) { //en caso de error viro al estado anterior
                DB::rollback();
//              $message=$e->getMessage();
                $message = 'Ocurrio un error y no se pudo aprobar la reserva';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);
            }

        } else return abort(403);
    }

    /**
     * Editar reserva
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function reservaEdit(Request $request, $id)
    {
        if ($request->user()->can('edit_reservas')) {
            Carbon::setLocale(config('app.locale'));

            $inscripcion = Inscripcion::where('id', $id)->with('factura', 'user', 'producto')->first();

            $mpagos_coll = Mpago::where('status', Mpago::ACTIVO)->get();
            //filtro las formas de pago
            $fp = $mpagos_coll->filter(function ($fp) {   //solo muestro los pagos CONTADO o WESTERN UNION
                if (stripos($fp->nombre, 'contado') !== false || stripos($fp->nombre, 'western') !== false || stripos($fp->nombre, 'tarjeta') !== false) {
                    return true; // true, el elemento se incluye, si retorna false, no se incluye
                }
            });
            $mpago = $fp->pluck('nombre', 'id');

            return view('inscripcion.reservas.reserva-edit', compact('inscripcion', 'mpago'));
        } else
            return abort(403);
    }


    /**
     * Actualizar la forma de pago de la reserva
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function reservaUpdate(Request $request, Inscripcion $inscripcion)
    {

        if ($request->user()->can('edit_reservas')) {

            try {

                $mpago_id = $request->input('mpago_id');

                $forma_anterior = $inscripcion->factura->mpago_id;

                DB::beginTransaction();

                $mpago = Mpago::findOrFail($mpago_id);

                //usuario logueado que modifico
                $inscripcion->user_edit = $request->user()->id;
                $inscripcion->update();

                $factura = Factura::where('id', $inscripcion->factura_id)->first();
                $factura->mpago()->associate($mpago);
                $factura->update();

                LogActivity::addToLog('Modificado la forma de pago de la Reserva,(inscripcion->id) ', $request->user(), $inscripcion->id, $factura->mpago_id);

                DB::commit();

                $notification = [
                    'message_toastr' => 'Se actualizó la forma de pago de la reserva correctamente.',
                    'alert-type' => 'success'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);

            } catch (\Exception $e) {
                DB::rollback();
//              $message=$e->getMessage();
                $message = 'Ocurrio un error y no se pudo modificar la forma de pago de la reserva';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->route('admin.inscripcions.reservas')->with($notification);
            }
        } else
            return abort(403);

    }


    /**
     * Exportar reservas al formato de Western Union
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reservasExport(Request $request)
    {
        if ($request->user()->can('add_reservas')) {
            // rango de fechas
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');

            $inscripciones = Inscripcion::with('factura', 'user', 'persona')
//            ->whereBetween('id', [$queryD, $queryH])
                ->where('status', Inscripcion::RESERVADA)
                ->orderBy('id');

            if (isset($desde)) {
                $start = Carbon::parse($desde)->startOfDay();
            }
            if (isset($hasta)) {
                $end = Carbon::parse($hasta)->endOfDay();
            }

            if (!isset($desde) && !isset($hasta)) {
                $inscripciones = $inscripciones->get();
            }
            if (isset($desde) && !isset($hasta)) {
                $inscripciones = $inscripciones->where('inscripcions.created_at', '>=', $desde)->get();
            }
            if ($desde == '' && $hasta) {
                $inscripciones = $inscripciones->where('inscripcions.created_at', '<=', $hasta)->get();
            }

            if ($desde && $hasta) {

                $inscripciones = $inscripciones->whereBetween('inscripcions.created_at', [$start, $end])->get();
            }

            $arrayExp = [];

            foreach ($inscripciones as $insc) {

                $arrayExp[] = [
                    'identificacion_fact' => $insc->factura->identificacion,
                    'nombre_fact' => $insc->factura->nombre,
                    'valor1' => number_format($insc->factura->total, 2, '.', ''),
                    'valor2' => number_format($insc->factura->total, 2, '.', ''),
                    'valor3' => number_format($insc->factura->total, 2, '.', ''),
                    'cobrar_hasta' => str_replace('-', '', $insc->created_at->addDay()->toDateString()),
                    'insc_id' => $insc->id,
                    'corredor' => $insc->persona->getFullName(),
                ];
            }

            Excel::create('Formato Excel Western Union- ' . Carbon::now() . '', function ($excel) use ($arrayExp) {

                $excel->sheet('WU', function ($sheet) use ($arrayExp) {

                    $sheet->setColumnFormat(array(
                        'A' => '@',
                        'B' => '@',
                        'C' => '@',
                        'D' => '@',
                        'E' => '@',
                        'F' => '@',
                        'G' => '@',
                        'H' => '@'
                    ));

                    $sheet->fromArray($arrayExp, null, 'A1', false, false);

                });
            })->export('xls');

            return view('admin.inscripcions.reservas', compact('desde', 'hasta'));
        } else
            return abort(403);
    }


    public function getPayment()
    {
        return view('inscripcion.online.paymentez');
    }


}