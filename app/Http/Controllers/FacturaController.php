<?php

namespace App\Http\Controllers;

use App\Escenario;
use App\Factura;
use App\Inscripcion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\ValidaRUC\ValidaRUC as ValidarRUC;


class FacturaController extends Controller
{
    public function __construct()
    {
//        setlocale(LC_TIME, 'es_ES.utf8');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('facturas.interna.index');
    }

    /**
     * Comprobantes datatable ajax
     */
    public function getAll(Request $request)
    {
        $user = $request->user();

        if ($user->can('view_comprobantes')) {

            if ($request->ajax()) {

                $comprobantes = Factura::
                with('user', 'persona', 'mpago', 'descuento')
                    ->where('facturas.status', Factura::PAGADA)//no mostrar las canceladas
                    ->select('facturas.*')
                ->orderBy('facturas.id', 'desc');

                $action_buttons = '
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                <div class="dropdown-menu dropdown-menu-left">
                @can(\'edit_comprobantes\')
                    <a class="dropdown-item" href="{{ route(\'facturas.edit\',[$id]) }}" data-toggle="tooltip" data-placement="top" title="Editar Comprobante">    
                        <i class="fa fa-pencil text-success"></i> Editar
                    </a>
                @endcan
                </div>
            </div>
                ';

                $datatable = Datatables::of($comprobantes)
                    ->addColumn('actions', $action_buttons)
                    ->rawColumns(['actions'])
                    ->setRowId('id');
                //Agregar variables a a la respuesta json del datatables
                if ($request->draw == 1) {
                    $formas = \App\Mpago::distinct('nombre')->pluck('nombre');
//                    $circuitos = \App\Circuito::distinct('circuito')->pluck('circuito');
                    $datatable->with([
                        'allMPago' => $formas,
//                        'allCircuitos' => $circuitos
                    ]);
                }

                // rango de fechas
                $desde = $datatable->request->get('desde');
                $hasta = $datatable->request->get('hasta');

                if ($desde == '' && $hasta == '') {
                    return $datatable->make(true);
                }
                if ($desde && $hasta == '') {
                    $datatable->where('facturas.created_at', 'like', "$desde%");
                }
                if ($desde == '' && $hasta) {
                    $datatable->where('facturas.created_at', 'like', "$hasta%");
                }

                if ($desde && $hasta) {
                    //para que incluya los dias desde el comienzo hasta el fin del dia
                    $start = Carbon::parse($desde)->startOfDay();
                    $end = Carbon::parse($hasta)->endOfDay();

                    $datatable->whereBetween('facturas.created_at', [$start, $end]);
                }

                return $datatable->make(true);
            }

        } else abort(403);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        return view('facturas.interna.edit', compact('factura'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Factura $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factura $factura)
    {
        $user = $request->user(); //usuario logueado que edita

        $ahora = Carbon::now();

        $rules = [
            'nombre' => 'required',
            'identificacion' => 'required',
            'email' => 'required|email',
            'telefono' => 'required',
            'direccion' => 'required'
        ];
//
        $messages = [
            'nombre.required' => 'El campo Nombres es obligatorio.',
            'identificacion.required' => 'El campo Identificación es obligatorio.',
            'email.required' => 'El campo Email es obligatorio. De lo contrario seleccione consumidor final',
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

        $nombres_fact = $request->input('nombre');
        $email_fact = $request->input('email');
        $direccion_fact = $request->input('direccion');
        $telefono_fact = $request->input('telefono');
        $num_doc_fact = $request->input('identificacion');

        try {

            DB::beginTransaction();

            $factura->nombre = $nombres_fact;
            $factura->email = $email_fact;
            $factura->direccion = $direccion_fact;
            $factura->telefono = $telefono_fact;
            $factura->identificacion = $num_doc_fact;
            $factura->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Comprobante actualizado correctamente.',
                'alert-type' => 'success'];
            return redirect()->route('facturas.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
//            $message = $e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error y no se pudo actualizar el comprobante.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factura $factura)
    {
        //
    }


    /**
     * @param Request $request
     */
    public function facturacionMasiva(Request $request)
    {
        $desde = $request->get('fecha_desde');
        $hasta = $request->get('fecha_hasta');

        $start = false;
        $end = false;
        if (isset($desde)) {
            $start = Carbon::parse($desde)->startOfDay();
        }
        if (isset($hasta)) {
            $end = Carbon::parse($hasta)->endOfDay();
        }


//        $comprobantes = Factura::with('user', 'persona', 'mpago', 'descuento', 'inscripciones')
//            ->where('status', Factura::PAGADA);

        $comprobantes = Inscripcion::with('factura', 'user', 'persona', 'producto', 'escenario')
             ->whereHas('factura', function($query){
                 $query->where('status', Factura::PAGADA);
             })
            ->groupBy('factura_id') ;//para cuando haya mas de una inscripcion con una sola factura

        if (!$start && !$end) { //no se escogio fecha
            //todos los comprobantes
            $comprobantes = $comprobantes->get();
        } elseif ($start && !$end) { //se escogio desde pero no hasta
            //todos los comprobantes superior a la fecha indicadad en desde
            $comprobantes = $comprobantes->where('created_at', '>=', $start)->get();
        } elseif (!$start && $end) { //no se escogio desde, solo hasta
            //todos los comprobantes con fecha menor o igual a la indicada en hasta
            $comprobantes = $comprobantes->where('created_at', '<=', $end)->get();
        } elseif ($start && $end) { //se escogio feche desde y fecha hasta
            //todos los comprobantes comprendidos entre las fechas
            $comprobantes = $comprobantes->whereBetween('created_at', [$start, $end])->get();
        }

        $comprobantesArray[] = ['codigopadre', 'codigo', 'nombre', 'nombrecomercial', 'RUC', 'Fecha', 'Referencia', 'Comentario',
            'CtaIngreso', 'Cantidad', 'Valor', 'Iva', 'DIRECCION', 'division', 'TipoCli', 'actividad', 'codvend', 'recaudador',
            'formadepago', 'estado', 'diasplazo', 'precio', 'telefono', 'fax', 'celular', 'e_mail', 'pais', 'provincia', 'ciudad',
            'CtaxCob', 'CtaxAnt', 'cupo', 'empresasri'
        ];

        foreach ($comprobantes as $comp) {  //inscipciones

            $ruc = $comp->factura->identificacion;
            $email = $comp->factura->email;

            $circuito=$comp->producto->circuito->circuito;
            $categoria=$comp->producto->categoria->categoria;

            $forma_pago=$comp->factura->mpago->nombre;

            //las inscripciones online y que no sean pagadas al contado tendran division=1002 sino el codigo del escenario
            if ($comp->inscripcion_type==Inscripcion::INSCRIPCION_ONLINE && stripos($forma_pago, 'contado')==false){
                $division=1002;
            }else {
                $division=(int)$comp->escenario->codigo;
            }

            if (ValidarRUC::valida_ruc($ruc) === "OK") {
                $ruc = $comp->factura->identificacion;
                $nombre=$comp->factura->nombre;
            } elseif (ValidarRUC::valida_ruc($ruc) === "CF" || ValidarRUC::valida_ruc($ruc) == "El formato es incorrecto") {
                $ruc = 999999999;
                $email = 'consumidor@final.mail';
                $nombre='CONSUMIDOR FINAL';
            }

            $comprobantesArray[] = [
                'codigopadre' => '',
                'codigo' => '',
                'nombre' => $nombre,
                'nombrecomercial' => $nombre,
                'RUC' => (int)$ruc,
//              'Fecha' => substr(str_replace('-','/',$comp->fecha1), 0, 10),
                'Fecha' => (string)$comp->created_at->format('d/m/Y'),  //$comp->created_at
                'Referencia' => 'INSCRIPCIÓN EN LA CARRERA GUAYACO RUNNER 2018'. '-' . $circuito . '/' .$categoria,
                'Comentario' => $comp->factura->numero,
                'CtaIngreso' => '6252499004001',
                'Cantidad' => 1,
                'Valor' => (float)$comp->factura->total,
                'Iva' => 'S',
                'DIRECCION' => $comp->factura->direccion,
                'division' => $division,
                'TipoCli' => 1,
                'actividad' => 1,
                'codvend' => '',
                'recaudador' => '',
                'formadepago' => $forma_pago,
                'estado' => 'A',
                'diasplazo' => 1,
                'precio' => 1,
                'telefono' => (string)$comp->factura->telefono,
                'fax' => '',
                'celular' => '',
                'e_mail' => $email,
                'pais' => 1,
                'provincia' => 1,
                'ciudad' => 4,
                'CtaxCob' => '1110101001',
                'CtaxAnt' => '2120307999',
                'cupo' => 500,
                'empresasri' => 'PERSONAS NO OBLIGADAS A LLEVAR CONTABILIDAD, FACTURA',
            ];

        }

        Excel::create('Facturación Masiva', function ($excel) use ($comprobantesArray) {

            $excel->sheet('Comprobantes', function ($sheet) use ($comprobantesArray) {

                $sheet->setColumnFormat([
                    'A' => 'General',
                    'B' => 'General',
                    'C' => 'General',
                    'D' => 'General',
                    'E' => '0',
                    'F' => 'dd/mm/yyyy;@',//@
                    'I' => '@',
                    'K' => '#,##0.00_-',
                    'N' => '0',
                    'O' => '0',
                    'P' => '0',
                    'U' => '0',
                    'V' => '0',
                    'AA' => '0',
                    'AB' => '0',
                    'AC' => '0',
                    'AF' => '#,##0.00_-',
                    'AD' => 'General',
                    'AE' => 'General'

                ]);

                $sheet->fromArray($comprobantesArray, null, 'A1', false, false);

            });
        })->export('xlsx');

    }



    public function getCuadre(Request $request){

        $escenarioSelect = Escenario::all();
        $escenarios=$escenarioSelect->pluck('escenario','id');

        $usuarioSelect = User::
            whereHas('roles', function($q){ //con rol=employee
                    $q->where('name', '=', 'employee');
                })
            ->select(DB::raw('concat (upper(first_name)," ",upper(last_name)) as nombres,id'))->get();
        $usuarios = $usuarioSelect->pluck('nombres', 'id');

        $fecha = $request->input('fecha');
        $escenario = $request->input('escenario');
        $usuario = $request->input('usuario');

        $cuadre = Factura::from('facturas as f')
            ->join('inscripcions as i', 'i.factura_id', '=', 'f.id')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('mpagos as p', 'p.id', '=', 'f.mpago_id')
            ->select('f.total', 'i.factura_id', 'i.user_id as uid', 'u.first_name', 'u.last_name', 'i.escenario_id', 'i.created_at', 'i.id', 'i.status', 'p.id as pagoID', 'p.nombre as forma','f.status as fstatus')
            ->where('i.status', Inscripcion::PAGADA)
            ->where('f.status', Factura::PAGADA)
            ->groupBy('i.factura_id');//agrupo por facturas de la tabla inscripciones xk hay varias insccripciones con una misma factura


        $insc_online_tarj= Factura::from('facturas as f')
            ->join('inscripcions as i', 'i.factura_id', '=', 'f.id')
            ->join('mpagos as p', 'p.id', '=', 'f.mpago_id')
            ->select('f.total', 'i.factura_id','f.created_at', 'i.status', 'i.inscripcion_type','f.status','f.transaction_id','f.payment_status')
            ->where('i.status', Inscripcion::PAGADA)
            ->whereNotNull('i.user_online')
            ->where('i.inscripcion_type', Inscripcion::INSCRIPCION_ONLINE)
            ->where('f.status', Factura::PAGADA)
            ->where('f.payment_status', Factura::PAYMENT_APPROVED)
            ->whereNotNull('f.transaction_id')
            ->groupBy('i.factura_id');

        if (!$fecha && !isset($escenario)){
            $cuadre=$cuadre->get();
            $insc_online_tarj=$insc_online_tarj->sum('f.total');
        }elseif ($fecha && !isset($escenario)){
            $cuadre=$cuadre->where('f.created_at','like', "%$fecha%")->get();
            $insc_online_tarj=$insc_online_tarj->where('f.created_at','like', "%$fecha%")->sum('f.total');
        }elseif (!$fecha && isset($escenario)){
            $cuadre=$cuadre->where('i.escenario_id',$escenario)->get();
            $insc_online_tarj=$insc_online_tarj->sum('f.total');
        }elseif ( $fecha && isset($escenario)){
            $cuadre=$cuadre->where('f.created_at','like', "$fecha%")
                ->where('i.escenario_id',$escenario)
                ->get();
            $insc_online_tarj=$insc_online_tarj->where('f.created_at','like', "%$fecha%")->sum('f.total');
        }

        $group = [];

        //crear array agrupando por el nombre de usuario  y agregar los valores de las facturas
        foreach ($cuadre as $c) {
            $user = $c->first_name . ' ' . $c->last_name;
            $i = $c->total;
            $forma = $c->forma;
            $group[$user][] = [
                "Nombre" => $user,
                "precio" => $i,
                "fpago" => $forma,
            ];
        }
        //sumar columnas para total por usuario y Total general
        $cuadreArray = [];
        $valorFinal = 0;
        $totalContado = 0;
        $totalTarjeta = 0;
        $totalWestern = 0;
        foreach ($group as $nombre => $fp) {
            $valorUsuario = 0;
            $valorContado = 0;
            $valorTarjeta = 0;
            $valorWestern = 0;
            foreach ($group[$nombre] as $key => $value) { // agrupar los valores de las facturas por usuario
                //acumulados
                if (stristr($value['fpago'], 'contado')) {
                    $valorContado += $value['precio']; //acumulado de contado para el usuario
                    $totalContado += $value['precio']; //acumulado total de contado
                }
                if (stristr($value['fpago'], 'tarjeta')) {
                    $valorTarjeta += $value['precio'];
                    $totalTarjeta += $value['precio'];
                }
                if (stristr($value['fpago'], 'western')) {
                    $valorWestern += $value['precio'];
                    $totalWestern += $value['precio'];
                }
                $valorUsuario += $value['precio']; //acumulado para el usuario actual
                $valorFinal += $value['precio']; //acumulado total
            }
            $cuadreArray [] = [
                "valor" => $valorUsuario,
                "usuario" => $nombre,
                "contado" => $valorContado,
                "tarjeta" => $valorTarjeta,
                "western" => $valorWestern
            ];
        }

        $total = [
            "totalWestern" => $totalWestern,
            "totalContado" => $totalContado,
            "totalTarjeta" => $totalTarjeta,
            "totalGeneral" => $valorFinal,
            "totalOnlineTarjeta"=>$insc_online_tarj
        ];

        return view('facturas.interna.cuadre', compact('escenarios', 'usuarios', 'escenario', 'usuario', 'fecha', 'cuadreArray','total','cuadre'));


    }

}
