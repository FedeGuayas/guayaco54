<?php

namespace App\Http\Controllers;

use App\Configuracion;
use App\Ejercicio;
use App\Impuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = Configuracion::where('status', Configuracion::ATIVO)->first();

        $ejer = Ejercicio::where('status', Ejercicio::ACTIVO)->get();
        $ejercicios = $ejer->pluck('year', 'id');

        $imp = Impuesto::where('status', Impuesto::ACTIVO)->get();
        $impuestos = $imp->pluck('nombre', 'id');

        return view('configuracion.index', compact('config', 'ejercicios', 'impuestos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $config_id = $request->input('config_id');

        if (isset($config_id)) { //existe, actualizar

            return $this->update($request, $config_id);

        } else {

            return $this->store($request);

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'ejercicio_id' => 'required',
            'impuesto_id' => 'required',
            'empresa' => 'required',
            'telefonos' => 'required',
            'ruc' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'nombre_contacto' => 'required',
        ];

        $messages = [
            'ejercicio_id.required' => 'El valor del campo ejercicio es requerido.',
            'impuesto_id.required' => 'El valor del campo impuesto es requerido.',
            'empresa.required' => 'El valor del campo empresa es requerido.',
            'telefonos.required' => 'El valor del campo telefonos es requerido.',
            'ruc.required' => 'El valor del campo ruc es requerido.',
            'email.required' => 'El valor del campo email es requerido.',
            'email.email' => 'El campo email no tiene un formato de email correcto.',
            'direccion.required' => 'El valor del campo diección es requerido.',
            'nombre_contacto.required' => 'El valor del campo nombre de contacto es requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        try {
            DB::beginTransaction();

            $ejercicio_id = $request->input('ejercicio_id');
            $ejercicio = Ejercicio::findOrFail($ejercicio_id);
            $impuesto_id = $request->input('impuesto_id');
            $impuesto = Impuesto::findOrFail($impuesto_id);

            $configuracion = new Configuracion();
            $configuracion->ejercicio_id = $ejercicio->id;
            $configuracion->impuesto_id = $impuesto->id;
            $configuracion->empresa = $request->input('empresa');
            $configuracion->telefonos = $request->input('telefonos');
            $configuracion->ruc = $request->input('ruc');
            $configuracion->email = $request->input('email');
            $configuracion->direccion = $request->input('direccion');
            $configuracion->nombre_contacto = $request->input('nombre_contacto');
            $configuracion->client_app_code=trim($request->input('client_app_code'));
            $configuracion->client_app_key=trim($request->input('client_app_key'));
            $configuracion->server_app_code=trim($request->input('server_app_code'));
            $configuracion->server_app_key=trim($request->input('server_app_key'));
            $configuracion->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'La configuración ha sido guardada',
                'alert-type' => 'success'];
            return back()->with($notification)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
//                $message='Ocurrio un error y no se pudo guardar la configuración';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Configuracion $configuracion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $config_id)
    {
        $rules = [
            'ejercicio_id' => 'required',
            'impuesto_id' => 'required',
            'empresa' => 'required',
            'telefonos' => 'required',
            'ruc' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'nombre_contacto' => 'required',
        ];

        $messages = [
            'ejercicio_id.required' => 'El valor del campo ejercicio es requerido.',
            'impuesto_id.required' => 'El valor del campo impuesto es requerido.',
            'empresa.required' => 'El valor del campo empresa es requerido.',
            'telefonos.required' => 'El valor del campo telefonos es requerido.',
            'ruc.required' => 'El valor del campo ruc es requerido.',
            'email.required' => 'El valor del campo email es requerido.',
            'email.email' => 'El campo email no tiene un formato de email correcto.',
            'direccion.required' => 'El valor del campo diección es requerido.',
            'nombre_contacto.required' => 'El valor del campo nombre de contacto es requerido.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return redirect()->route('admin.configurations.index')->with($notification)->withInput();
        }

        try {
            DB::beginTransaction();

            $ejercicio_id = $request->input('ejercicio_id');
            $ejercicio = Ejercicio::findOrFail($ejercicio_id);
            $impuesto_id = $request->input('impuesto_id');
            $impuesto = Impuesto::findOrFail($impuesto_id);

            $configuracion = Configuracion::findOrFail($config_id);
            $configuracion->ejercicio_id = $ejercicio->id;
            $configuracion->impuesto_id = $impuesto->id;
            $configuracion->empresa = $request->input('empresa');
            $configuracion->telefonos = $request->input('telefonos');
            $configuracion->ruc = $request->input('ruc');
            $configuracion->email = $request->input('email');
            $configuracion->direccion = $request->input('direccion');
            $configuracion->nombre_contacto = $request->input('nombre_contacto');
            $configuracion->client_app_code=trim($request->input('client_app_code'));
            $configuracion->client_app_key=trim($request->input('client_app_key'));
            $configuracion->server_app_code=trim($request->input('server_app_code'));
            $configuracion->server_app_key=trim($request->input('server_app_key'));
            $configuracion->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'La configuración ha sido actualizada',
                'alert-type' => 'success'];
            return redirect()->route('admin.configurations.index')->with($notification)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
//                $message='Ocurrio un error y no se pudo actualizar la configuración';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

    }

}
