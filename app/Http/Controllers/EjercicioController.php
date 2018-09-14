<?php

namespace App\Http\Controllers;

use App\Ejercicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EjercicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'year'=>'required|max:4|unique:ejercicios,year'
        ];

        $messages = [
            'year.unique' => 'El valor del campo año ya está en uso.',
            'year.max' => 'El campo año no debe contener más de 4 caracteres.',
            'year.required'=>'El valor del campo año es requerido.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $year=$request->input('year');

        try {
            DB::beginTransaction();

            $ejer=new Ejercicio();
            $ejer->year=$year;
            $ejer->status=Ejercicio::ACTIVO;
            $ejer->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'El año se guardo correctamente',
                'alert-type' => 'success'];
            return redirect()->route('admin.configurations.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//            $message=$e->getMessage();
            $message='Ocurrio un error y no se pudo guardar el año';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ejercicio  $ejercicio
     * @return \Illuminate\Http\Response
     */
    public function show(Ejercicio $ejercicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ejercicio  $ejercicio
     * @return \Illuminate\Http\Response
     */
    public function edit(Ejercicio $ejercicio)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ejercicio  $ejercicio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'year'=>'required|max:4|unique:ejercicios,year,'.$id
        ];

        $messages = [
            'year.unique' => 'El valor del campo año ya está en uso.',
            'year.max' => 'El campo año no debe contener más de 4 caracteres.',
            'year.required'=>'El valor del campo año es requerido.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $year=$request->input('year');
        $status=$request->input('status');

        try {
            DB::beginTransaction();

            $ejer=Ejercicio::findOrFail($id);
            $ejer->year=$year;
            $status=='on' ? $ejer->status=Ejercicio::ACTIVO : $ejer->status=Ejercicio::INACTIVO;
            $ejer->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'El año se actualizo correctamente',
                'alert-type' => 'success'];
            return redirect()->route('ejercicios.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//            $message=$e->getMessage();
            $message='Ocurrio un error y no se pudo actualizar el año';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ejercicio  $ejercicio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ejer=Ejercicio::findOrFail($id);
        $ejer->status==Ejercicio::INACTIVO ? $ejer->status=Ejercicio::ACTIVO : $ejer->status=Ejercicio::INACTIVO;
        $ejer->update();
        return response()->json(['data'=>$ejer],200);

    }
}
