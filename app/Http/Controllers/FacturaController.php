<?php

namespace App\Http\Controllers;

use App\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;

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
                    ->where('status','!=',Factura::CANCELADA) //no mostrar las canceladas
                    ->select('facturas.*');

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
    public function comprobantesExcel(Request $request)
    {
        $escenarioSelect = ['' => 'Seleccione el escenario'] + Escenario::lists('escenario', 'id')->all();
        $usuarioSelect = ['' => 'Seleccione el usuario'] + User::lists('nombre', 'id')->all();
        if ($request) {
            $fecha = $request->get('fecha');
            $escenario = $request->get('escenario');
            $usuario = $request->get('usuario');
            $comprobantes = DB::table('comprobantes as c')
                ->join('users as u', 'u.id', '=', 'c.user_id')
                ->join('escenarios as e', 'e.id', '=', 'u.escenario_id')
                ->join('inscripciones as i', 'i.id', '=', 'c.inscripcion_id')
                ->select('c.id as comp_id', 'c.nombres', 'c.apellidos', 'c.num_doc', 'c.email', 'c.telefono', 'c.direccion', 'e.id', 'u.id', 'i.costo',
                    'c.created_at as fecha1', 'i.form_pago', 'i.escenario', 'u.nombre as usuario', 'i.num_corredor as numero', 'c.user_id', 'c.cuenta_id')
                ->where('c.created_at', 'LIKE', '%' . $fecha . '%')
                ->where('e.id', 'LIKE', '%' . $escenario . '%')
                ->where('u.id', 'LIKE', '%' . $usuario . '%')
                ->where('form_pago', '<>', '')
                ->get();

//            $comprobantesArray[] = ['Número', 'Nombres', 'Apellidos', 'CI', 'Correo', 'Teléfono', 'Dirección', 'Costo', 'Forma Pago', 'Escenario', 'Usuario', 'Fecha'];

            $comprobantesArray[] = ['codigopadre', 'codigo', 'nombre', 'nombrecomercial', 'RUC', 'Fecha', 'Referencia', 'Comentario',
                'CtaIngreso', 'Cantidad', 'Valor', 'Iva', 'DIRECCION', 'division', 'TipoCli', 'actividad', 'codvend', 'recaudador',
                'formadepago', 'estado', 'diasplazo', 'precio', 'telefono', 'fax', 'celular', 'e_mail', 'pais', 'provincia', 'ciudad',
                'CtaxCob', 'CtaxAnt', 'cupo', 'empresasri'
            ];

            foreach ($comprobantes as $comp) {
//                $comprobantesArray[] = [
//                    'num' => $comp->numero,
//                    'nombres' => $comp->nombres,
//                    'apellidos' => $comp->apellidos,
//                    'ci' => $comp->num_doc,
//                    'email' => $comp->email,
//                    'telefono' => $comp->telefono,
//                    'direccion' => $comp->direccion,
//                    'valor' => $comp->costo,
//                    'forma_pago' => $comp->form_pago,
//                    'escenario' => $comp->escenario,
//                    'usuario' => $comp->usuario,
//                    'fecha_i' => $comp->fecha1,
//                ];

                $ruc = $comp->num_doc;

                if ( validaRUC($ruc) === "OK") {
                    $ruc = $comp->num_doc;
                } elseif ( validaRUC($ruc) === "CF" || validaRUC($ruc) == "El formato es incorrecto") {
                    $ruc=999999999;
                }

                $comprobantesArray[] = [
                    'codigopadre' => '',
                    'codigo' => '',
                    'nombre' => $comp->nombres . ' ' . $comp->apellidos,
                    'nombrecomercial' => $comp->nombres . ' ' . $comp->apellidos,
                    'RUC' => floatval($ruc),
//                    'Fecha' => substr(str_replace('-','/',$comp->fecha1), 0, 10),
                    'Fecha' => $comp->fecha1,
                    'Referencia' => 'PAGO POR INSCRIPCIÓN EN CARRERA GUAYACO RUNNER 2017',
                    'Comentario' => 'GUAYACORUNNER',
                    'CtaIngreso' => '6252499004001',//Actualizar esto 6252499004001
                    'Cantidad' => 1,
                    'Valor' => (float)$comp->costo,
                    'Iva' => 'S',
                    'DIRECCION' => 'GUAYAQUIL',
                    'division' => 2004,
                    'TipoCli' => 1,
                    'actividad' => 1,
                    'codvend' => '',
                    'recaudador' => '',
                    'formadepago' => $comp->form_pago,
                    'estado' => 'A',
                    'diasplazo' => 1,
                    'precio' => 1,
                    'telefono' => 'NO',
                    'fax' => '',
                    'celular' => '',
                    'e_mail' => $comp->email,
                    'pais' => 1,
                    'provincia' => 1,
                    'ciudad' => 4,
                    'CtaxCob' => '1110101001',
                    'CtaxAnt' => '210307999',
                    'cupo' => 500,
                    'empresasri' => 'PERSONAS NO OBLIGADAS A LLEVAR CONTABILIDAD, FACTURA',
                ];
            }

            Excel::create('Comprobantes Excel', function ($excel) use ($comprobantesArray) {

                $excel->sheet('Comprobantes', function ($sheet) use ($comprobantesArray) {

                    $sheet->setColumnFormat([
                        'A' => 'General',
                        'B' => 'General',
                        'C' => 'General',
                        'D' => 'General',
                        'E' => '0',
                        'F' => 'dd/mm/yyyy;@',
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

//            return view('runner.comprobantes.index', compact('comprobantes', 'fechaD', 'fechaH', 'escenario', 'escenarioSelect'));
        }
    }
}
