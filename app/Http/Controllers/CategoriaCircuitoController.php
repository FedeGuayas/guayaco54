<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\CategoriaCircuito;
use App\Circuito;
use App\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaCircuitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias=Categoria::with('circuitos')->where('status',Categoria::ACTIVO)->get();
        return view('categoria_circuito.index',compact('categorias'));
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

        return view('categoria_circuito.create',compact('categorias','circuitos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cat_id=$request->input('categoria_id');
        $circuitos_id=$request->input('circuitos_id');

        if (!isset($circuitos_id)){
            $notification = [
                'message_toastr' => 'Debe seleccionar el/los circuitos',
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

        try {
            DB::beginTransaction();

            $categoria = Categoria::where('id', $cat_id)->where('status', Categoria::ACTIVO)->first();

            if (count($categoria) > 0 ) {
                $categoria->circuitos()->attach($circuitos_id);
//                $categoria->circuitos()->sync($circuitos_id);
            }

            DB::Commit();
            $notification = [
                'message_toastr' => 'Se vincularon los circuitos  satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('categoria-circuito.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//                        $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error alt tratar de vincular los circuitos a la categoría.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CategoriaCircuito  $categoriaCircuito
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $categoria=Categoria::findOrFail($id);
        $circuitos=Circuito::all();

        return view('categoria_circuito.edit',compact('categoria','','circuitos'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CategoriaCircuito  $categoriaCircuito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $circuitos_id=$request->input('circuitos');

        try {
            DB::beginTransaction();

            $categoria = Categoria::where('id', $id)->where('status', Categoria::ACTIVO)->first();

            if (count($categoria) > 0 ) {
                $categoria->circuitos()->sync($circuitos_id);
            }

            DB::Commit();
            $notification = [
                'message_toastr' => 'Se actualizaron los vinculos  satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('categoria-circuito.index')->with($notification);

        }catch (\Exception $e){
            DB::rollBack();
//                        $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error alt tratar de actualizar los circuitos a la categoría.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

}
