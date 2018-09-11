<?php

namespace App\Http\Controllers;

use App\Deporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deportes=Deporte::all();
        return view('deportes.index',compact('deportes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('deportes.create');
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
            'deporte' => 'required|max:60|unique:deportes,deporte'
        ];

        $messages = [
            'deporte.required' => 'EL deporte es un campo requerido',
            'deporte.unique' => 'EL nombre del deporte ya se encuentra en uso',
            'deporte.max'=>'EL nombre del deporte es demasiado largo, no debe sobrepasar los 60 caracteres',
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

            $deporte=new Deporte();
            $deporte->deporte=$request->input('deporte');
            $deporte->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Deporte creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('deportes.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del deporte.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deporte  $deporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Deporte $deporte)
    {
        return view('deportes.edit',compact('deporte'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deporte  $deporte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deporte $deporte)
    {
        $rules = [
            'deporte' => 'required|max:60|unique:deportes,deporte,'.$deporte->id
        ];

        $messages = [
            'deporte.required' => 'EL deporte es un campo requerido',
            'deporte.unique' => 'EL nombre del deporte ya se encuentra en uso',
            'deporte.max'=>'EL nombre del deporte es demasiado largo, no debe sobrepasar los 60 caracteres',
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

            $deporte->deporte=$request->input('deporte');
            $deporte->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Deporte actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('deportes.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización del deporte.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Cambiar el estado del deporte
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($id)
    {
        $deporte=Deporte::findOrFail($id);
        $deporte->status==Deporte::INACTIVO ? $deporte->status=Deporte::ACTIVO : $deporte->status=Deporte::INACTIVO;
        $deporte->update();
        return response()->json(['data'=>$deporte],200);
    }
}
