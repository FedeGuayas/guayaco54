<?php

namespace App\Http\Controllers;

use App\Talla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TallaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tallas=Talla::all();
        return view('tallas.index',compact('tallas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tallas.create');
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
            'talla' => 'required',
            'color' => 'required',
            'stock' => 'required',
        ];

        $messages = [
            'talla.required' => 'La talla es un campo requerido',
            'color.required' => 'EL color de la camiseta es obligatoria',
            'stock.required' => 'El stock es obligatorio',
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

            $talla = new Talla();
            $talla->talla = $request->get('talla');
            $talla->stock = $request->get('stock');
            $talla->color = $request->get('color');
            if ($request->input('stock') > 0) {
                $talla->status=Talla::ACTIVO;
            }
            $talla->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Talla creada satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->back()->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//                        $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación de la talla.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Talla  $talla
     * @return \Illuminate\Http\Response
     */
    public function edit(Talla $talla)
    {
        return view('tallas.edit',compact('talla'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Talla  $talla
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Talla $talla)
    {
        $rules = [
            'talla' => 'required',
            'color' => 'required',
            'stock' => 'required|numeric',
        ];

        $messages = [
            'talla.required' => 'La talla es un campo requerido',
            'color.required' => 'EL color de la camiseta es obligatoria',
            'stock.required' => 'El stock es obligatorio',
            'stock.numeric'=>'EL Stock debe ser un número'
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

            $talla->talla = $request->get('talla');
            $talla->stock = $request->get('stock');
            $talla->color = $request->get('color');
            if ($request->input('stock') > 0) {
                $talla->status=Talla::ACTIVO;
            }else {
                $talla->status=Talla::INACTIVO;
            }
            $talla->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Talla actualizada satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('tallas.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualizacion de la talla.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Talla  $talla
     * @return \Illuminate\Http\Response
     */
    public function destroy(Talla $talla)
    {
        $talla->delete();
        return response()->json(['data'=>$talla],200);
    }


    /**
     * Cambiar el estado de la talla
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($id)
    {
        $talla=Talla::findOrFail($id);
        $talla->status==Talla::INACTIVO ? $talla->status=Talla::ACTIVO : $talla->status=Talla::INACTIVO;
        $talla->update();
        return response()->json(['data'=>$talla],200);
    }


    /**BACKEND
     * Muestra el stock de la talla
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function getTallaStock(Request $request)
    {

        if ($request->ajax()){

            $talla=Talla::where('status',Talla::ACTIVO)
                ->where('id',$request->input('talla_id'))
                ->first();

            $talla ? $stock=$talla->stock : $stock=0;

            return response()->json(['data' => $stock], 200);
        }

    }

}
