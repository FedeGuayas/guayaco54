<?php

namespace App\Http\Controllers;

use App\Escenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EscenarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $escenarios=Escenario::all();
        return view('escenarios.index',compact('escenarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('escenarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'escenario' => 'required|max:20|unique:escenarios,escenario'
        ];

        $messages = [
            'escenario.required' => 'EL escenario es un campo requerido',
            'escenario.unique' => 'EL nombre del escenario ya se encuentra en uso',
            'escenario.max'=>'EL nombre del escenario es demasiado largo, no debe sobrepasar los 20 caracteres',
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

            $escenario=new Escenario();
            $escenario->escenario=$request->input('escenario');
            $escenario->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Escenario creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('escenarios.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del escenario.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Escenario  $escenario
     * @return \Illuminate\Http\Response
     */
    public function edit(Escenario $escenario)
    {
        return view('escenarios.edit',compact('escenario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escenario  $escenario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escenario $escenario)
    {
        $rules = [
            'escenario' => 'required|max:20|unique:escenarios,escenario,'.$escenario->id
        ];

        $messages = [
            'escenario.required' => 'EL escenario es un campo requerido',
            'escenario.unique' => 'EL nombre del escenario ya se encuentra en uso',
            'escenario.max'=>'EL nombre del escenario es demasiado largo, no debe sobrepasar los 20 caracteres',
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

            $escenario->escenario=$request->input('escenario');
            $escenario->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Escenario actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('escenarios.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización del escenario.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Cambiar el estado del escenario
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($id)
    {
        $escenario=Escenario::findOrFail($id);
        $escenario->status==Escenario::INACTIVO ? $escenario->status=Escenario::ACTIVO : $escenario->status=Escenario::INACTIVO;
        $escenario->update();
        return response()->json(['data'=>$escenario],200);
    }
}
