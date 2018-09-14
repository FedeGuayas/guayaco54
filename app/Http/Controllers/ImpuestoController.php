<?php

namespace App\Http\Controllers;

use App\Impuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImpuestoController extends Controller
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
            'nombre'=>'required',
            'porciento'=>'required|numeric',
            'divisor'=>'required|numeric'
        ];

        $messages = [
            'nombre.required'=>'El valor del campo nombre es requerido.',
            'porciento.required'=>'El valor del campo porciento es requerido.',
            'porciento.numeric'=>'El porciento debe ser un campo numérico.',
            'divisor.required'=>'El valor del campo divisor es requerido.',
            'divisor.numeric'=>'El divisor debe ser un campo numérico.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $nombre=$request->input('nombre');
        $porciento=$request->input('porciento');
        $divisor=$request->input('divisor');

        try {
            DB::beginTransaction();

            $imp=new Impuesto();
            $imp->nombre=$nombre;
            $imp->porciento=$porciento;
            $imp->divisor=$divisor;
            $imp->status=Impuesto::ACTIVO;
            $imp->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'El impuesto se guardo correctamente',
                'alert-type' => 'success'];
            return redirect()->route('admin.configurations.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            $message=$e->getMessage();
//            $message='Ocurrio un error y no se pudo guardar el impuesto';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Impuesto $impuesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Impuesto $impuesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nombre'=>'required',
            'porciento'=>'required|numeric',
            'divisor'=>'required|numeric'
        ];

        $messages = [
            'nombre.required'=>'El valor del campo nombre es requerido.',
            'porciento.required'=>'El valor del campo porciento es requerido.',
            'porciento.numeric'=>'El porciento debe ser un campo numérico.',
            'divisor.required'=>'El valor del campo divisor es requerido.',
            'divisor.numeric'=>'El divisor debe ser un campo numérico.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $nombre=$request->input('nombre');
        $porciento=$request->input('porciento');
        $divisor=$request->input('divisor');
        $status=$request->input('status');

        try {
            DB::beginTransaction();

            $imp=Impuesto::findOrFail($id);
            $imp->nombre=$nombre;
            $imp->porciento=$porciento;
            $imp->divisor=$divisor;
            $status=='on' ? $imp->status=Impuesto::ACTIVO : $imp->status=Impuesto::INACTIVO;
            $imp->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'El impuesto se actualizo correctamente',
                'alert-type' => 'success'];
            return redirect()->route('taxes.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//            $message=$e->getMessage();
            $message='Ocurrio un error y no se pudo actualizar el impuesto';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Impuesto  $impuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imp=Impuesto::findOrFail($id);
        $imp->status==Impuesto::INACTIVO ? $imp->status=Impuesto::ACTIVO : $imp->status=Impuesto::INACTIVO;
        $imp->update();
        return response()->json(['data'=>$imp],200);
    }
}
