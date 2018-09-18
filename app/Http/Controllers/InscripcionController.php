<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Deporte;
use App\Inscripcion;
use App\Mpago;
use App\Producto;
use App\Talla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
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
    public function create(Request $request)
    {
        $user = $request->user();

        if (!isset($user->persona)) {//no tiene perfil, debe crearlo antes de inscribirse
            $notification = [
                'message_toastr' => 'Debe completar su perfil antes de hacer alguna inscripción',
                'alert-type' => 'error'];
            return redirect()->route('getProfile')->with($notification);
        }

        $edad = $user->persona->getEdad();

        $cat_all = Categoria::where('status', Categoria::ACTIVO)
            ->where([
                ['edad_start', '<=', $edad],
                ['edad_end', '>=', $edad],
            ])->get();

        $categorias = $cat_all->pluck('categoria', 'id');

        $tallas_all = Talla::where('status', Talla::ACTIVO)
            ->where('stock', '>', 0)
            ->select(DB::raw('concat (talla," - ",color) as talla,id'))
            ->get();
        $tallas = $tallas_all->pluck('talla', 'id');

        $deporte_all = Deporte::where('status', Deporte::ACTIVO)->get();
        $deportes = $deporte_all->pluck('deporte', 'id');

        $mp=Mpago::where('status',Mpago::ACTIVO)->get();
        $formas_pago=$mp->pluck('nombre','id');

        $perfil=$user->persona;

        return view('inscripcion.online.create', compact('categorias', 'tallas', 'deportes','perfil','formas_pago'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inscripcion $inscripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inscripcion $inscripcion)
    {
        //
    }

    /**
     * Obtener lo circuitos para la categoia seleccionada
     */
    public function getCategoriaCircuito(Request $request)
    {

        if ($request->ajax()) {

            $circuitos = Producto::with('circuito')
                ->where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('id'))
                ->get();

            $categoria = Categoria::where('id', $request->input('id'))->first();

            $deportista = false;
            if (stristr($categoria->categoria, 'deport')) {
                $deportista = true;
            }

            return response()->json(['data' => $circuitos, 'deportista' => $deportista], 200);
        }
    }


    /**
     * Obtener el costo de la inscripcion
     */
    public function userOnlineCosto(Request $request)
    {

        if ($request->ajax()) {

            $producto = Producto::where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('categoria_id'))
                ->where('circuito_id', $request->input('circuito_id'))
                ->first();

            $costo = 0;
            if ($producto) {
                $costo = number_format($producto->price, 2, '.', ' ');
            }

            return response()->json(['data' => $costo], 200);
        }
    }


    //Mostrar stock de tallas
    public function tallaStockUpdate(Request $request)
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
