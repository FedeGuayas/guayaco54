<?php

namespace App\Http\Controllers;

use App\Descuento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DescuentoController extends Controller
{
    /**
     * DescuentoController constructor.
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
        $descuentos=Descuento::all();
        return view('descuentos.index',compact('descuentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('descuentos.create');
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
            'nombre' => 'required|unique:descuentos,nombre',
            'porciento' => 'required|integer',
        ];

        $messages = [
            'nombre.required' => 'El nombre del descuento es un campo requerido',
            'nombre.unique'=>'EL nombre del descuento  ya se encuentra en uso y debe ser único',
            'porciento.required' => 'el porciento de descuento es obligatoria',
            'porciento.integer'=>'El porciento debe ser un número entero'
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

            $descuento=new Descuento();
            $descuento->nombre=$request->input('nombre');
            $descuento->porciento=$request->input('porciento');
            $descuento->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Descuento creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('descuentos.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del descuento.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Descuento $descuento)
    {
        return view('descuentos.edit',compact('descuento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descuento $descuento)
    {
        $rules = [
            'nombre' => 'required|unique:descuentos,nombre,'.$descuento->id,
            'porciento' => 'required|integer',
        ];

        $messages = [
            'nombre.required' => 'El nombre del descuento es un campo requerido',
            'nombre.unique'=>'EL nombre del descuento  ya se encuentra en uso y debe ser único',
            'porciento.required' => 'el porciento de descuento es obligatoria',
            'porciento.integer'=>'El porciento debe ser un número entero'
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

            $descuento->nombre=$request->input('nombre');
            $descuento->porciento=$request->input('porciento');
            $descuento->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Descuento actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('descuentos.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización del descuento.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descuento $descuento)
    {
        $descuento->status==Descuento::INACTIVO ? $descuento->status=Descuento::ACTIVO : $descuento->status=Descuento::INACTIVO;
        $descuento->update();
        return response()->json(['data'=>$descuento],200);
    }
}
