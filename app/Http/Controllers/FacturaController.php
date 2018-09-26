<?php

namespace App\Http\Controllers;

use App\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class FacturaController extends Controller
{
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
                    ->select('facturas.*');

                $action_buttons = '
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                <div class="dropdown-menu dropdown-menu-left">
                 @can(\'view_comprobantes\')
                    <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Imprimir Comprobante">
                        <i class="fa fa-print fa-2x text-dark"></i> Imprimir
                    </a>
                @endcan
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
}
