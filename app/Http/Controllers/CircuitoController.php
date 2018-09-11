<?php

namespace App\Http\Controllers;

use App\Circuito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CircuitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $circuitos=Circuito::all();
        return view('circuitos.index',compact('circuitos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('circuitos.create');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'circuito' => 'required|unique:circuitos,circuito',
            'title' => 'required',
        ];

        $messages = [
            'circuito.required' => 'El circuito es un campo requerido',
            'title.required' => 'El titulo es un campo requerido',
            'circuito.unique'=>'EL nombre del circuito  ya se encuentra en uso y debe ser único',
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

            $circuito=new Circuito();
            $circuito->circuito=$request->input('circuito');
            $circuito->title=$request->input('title');
            $circuito->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Circuito creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('circuitos.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del circuito.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Circuito  $circuito
     * @return \Illuminate\Http\Response
     */
    public function edit(Circuito $circuito)
    {
        return view('circuitos.edit',compact('circuito'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Circuito  $circuito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Circuito $circuito)
    {
        $rules = [
            'circuito' => 'required|unique:circuitos,circuito,'.$circuito->id,
            'title' => 'required',
        ];

        $messages = [
            'circuito.required' => 'El circuito es un campo requerido',
            'title.required' => 'El titulo es un campo requerido',
            'circuito.unique'=>'EL nombre del circuito  ya se encuentra en uso y debe ser único',
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

            $circuito->circuito=$request->input('circuito');
            $circuito->title=$request->input('title');
            $circuito->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Circuito actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('circuitos.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización del circuito.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Cambiar el estado del circuito
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($id)
    {
        $circuito=Circuito::findOrFail($id);
        $circuito->status==Circuito::INACTIVO ? $circuito->status=Circuito::ACTIVO : $circuito->status=Circuito::INACTIVO;
        $circuito->update();
        return response()->json(['data'=>$circuito],200);
    }
}
