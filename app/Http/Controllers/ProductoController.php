<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Circuito;
use App\Configuracion;
use App\Ejercicio;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos=Producto::with('categoria','circuito','ejercicio','inscripcions')->get();

        return view('productos.index',compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias_all=Categoria::where('status',Categoria::ACTIVO)->get();
        $categorias=$categorias_all->pluck('categoria','id');

        $circuitos=Circuito::where('status',Circuito::ACTIVO)->get();

        $config=Configuracion::with('ejercicio','impuesto')->where('status',Configuracion::ATIVO)->first();

        return view('productos.create',compact('categorias','circuitos','config'));
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
            'ejercicio_id' => 'required',
            'categoria_id' => 'required',
            'circuito' => 'required',
            'price' => 'required|numeric',
        ];

        $messages = [
            'ejercicio_id.required' => 'El valor del campo ejercicio es requerido.',
            'categoria_id.required' => 'El valor del campo categoría es requerido.',
            'circuito.required' => 'El valor del campo circuito es requerido.',
            'price.required' => 'El valor del campo costo es requerido.',
            'price.numeric' => 'El valor del campo costo debe ser un número.',

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
            $categoria_id = $request->input('categoria_id');
            $categoria = Categoria::findOrFail($categoria_id);
            $circuito_id = $request->input('circuito');
            $circuito = Circuito::findOrFail($circuito_id);


            $producto = new Producto();
            $producto->ejercicio()->associate($ejercicio);
            $producto->categoria()->associate($categoria);
            $producto->circuito()->associate($circuito);
            $producto->description=$request->input('description');
            $producto->price=$request->input('price');
            $producto->image=null;
            $producto->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Carrera guardada',
                'alert-type' => 'success'];
            return back()->with($notification)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
//            $message = $e->getMessage();
                $message='Ocurrio un error y no se pudo guardar los datos';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $categorias_all=Categoria::where('status',Categoria::ACTIVO)->get();
        $categorias=$categorias_all->pluck('categoria','id');

        $circuitos=Circuito::where('status',Circuito::ACTIVO)->get();

        $config=Configuracion::with('ejercicio','impuesto')->where('status',Configuracion::ATIVO)->first();

        return view('productos.edit',compact('categorias','circuitos','config','producto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $rules = [
            'ejercicio_id' => 'required',
            'categoria_id' => 'required',
            'circuito' => 'required',
            'price' => 'required|numeric',
        ];

        $messages = [
            'ejercicio_id.required' => 'El valor del campo ejercicio es requerido.',
            'categoria_id.required' => 'El valor del campo categoría es requerido.',
            'circuito.required' => 'El valor del campo circuito es requerido.',
            'price.required' => 'El valor del campo costo es requerido.',
            'price.numeric' => 'El valor del campo costo debe ser un número.',

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
            $categoria_id = $request->input('categoria_id');
            $categoria = Categoria::findOrFail($categoria_id);
            $circuito_id = $request->input('circuito');
            $circuito = Circuito::findOrFail($circuito_id);

            $producto->ejercicio()->associate($ejercicio);
            $producto->categoria()->associate($categoria);
            $producto->circuito()->associate($circuito);
            $producto->description=$request->input('description');
            $producto->price=$request->input('price');
            $producto->image=null;
            $producto->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Producto actualizado',
                'alert-type' => 'success'];
            return back()->with($notification)->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
//            $message = $e->getMessage();
            $message='Ocurrio un error y no se pudo actualizar los datos';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto=Producto::findOrFail($id);
        $producto->status==Producto::INACTIVO ? $producto->status=Producto::ACTIVO : $producto->status=Producto::INACTIVO;
        $producto->update();
        return response()->json(['data'=>$producto],200);
    }
}
