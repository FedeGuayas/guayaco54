<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias=Categoria::all();
        return view('categorias.index',compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categorias.create');
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
            'categoria' => 'required|unique:categorias,categoria',
            'edad_start' => 'required|numeric',
            'edad_end' => 'required|numeric',
        ];

        $messages = [
            'categoria.required' => 'La categoría es un campo requerido',
            'categoria.unique'=>'EL nombre de la categoría  ya se encuentra en uso y debe ser único',
            'edad_start.required' => 'La edad de inicio de la categoría es obligatoria',
            'edad_start.numeric'=>'La edad de inicio debe ser un número',
            'edad_end.required' => 'La edad de fin de la categoría es obligatoria',
            'edad_end.numeric'=>'La edad de fin debe ser un número'
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

            $categoria=new Categoria();
            $categoria->categoria=$request->input('categoria');
            $categoria->edad_start=$request->input('edad_start');
            $categoria->edad_end=$request->input('edad_end');
            $categoria->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Categoría creada satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('categorias.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación de la categoría.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit',compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        $rules = [
            'categoria' => 'required|unique:categorias,categoria,'.$categoria->id,
            'edad_start' => 'required|numeric',
            'edad_end' => 'required|numeric',
        ];

        $messages = [
            'categoria.required' => 'La categoría es un campo requerido',
            'categoria.unique'=>'EL nombre de la categoría  ya se encuentra en uso y debe ser único',
            'edad_start.required' => 'La edad de inicio de la categoría es obligatoria',
            'edad_start.numeric'=>'La edad de inicio debe ser un número',
            'edad_end.required' => 'La edad de fin de la categoría es obligatoria',
            'edad_end.numeric'=>'La edad de fin debe ser un número'
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

            $categoria->categoria=$request->input('categoria');
            $categoria->edad_start=$request->input('edad_start');
            $categoria->edad_end=$request->input('edad_end');
            $categoria->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Categoría actualizada satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('categorias.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización de la categoría.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Cambiar el estado de la categoria
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatus($id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->status==Categoria::INACTIVO ? $categoria->status=Categoria::ACTIVO : $categoria->status=Categoria::INACTIVO;
        $categoria->update();
        return response()->json(['data'=>$categoria],200);
    }
}
