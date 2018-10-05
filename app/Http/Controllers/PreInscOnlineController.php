<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Deporte;
use App\Mpago;
use App\Producto;
use App\Talla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreInscOnlineController extends Controller
{
    /**
     * PreInscOnlineController constructor.
     */
    public function __construct()
    {

    }


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
     * Muestra el formulario de Inscripcion Online
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();

        if (!isset($user->persona)) {//no tiene perfil, debe crearlo antes de inscribirse
            $notification = [
                'message_toastr' => 'Debe completar su perfil para poder hacer alguna inscripción',
                'alert-type' => 'error'];
            return redirect()->route('getProfile')->with($notification);
        }

        $edad = $user->persona->getEdad();

        $cat_all = Categoria::where('status', Categoria::ACTIVO)
            ->where('categoria', 'NOT LIKE', '%deport%')//en online no se tendra en cuenta la categoria Deportistas
            ->where([
                ['edad_start', '<=', $edad],
                ['edad_end', '>=', $edad],
            ])->get();

        $categorias = $cat_all->pluck('categoria', 'id');

        $tallas_all = Talla::where('status', Talla::ACTIVO)
            ->where('stock', '>', 0)
            ->select(DB::raw('concat (talla," - ",upper(color)) as talla,id'))
            ->get();
        $tallas = $tallas_all->pluck('talla', 'id');


        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');

        $perfil = $user->persona;

        return view('inscripcion.online.create', compact('categorias', 'tallas', 'perfil', 'formas_pago'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     *   Obtener lo circuitos para la categoria seleccionada
     * En este caso no se tienen en cuenta los deportes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriaCircuito(Request $request)
    {
        if ($request->ajax()) {

            $circuitos = Producto::with('circuito')
                ->where('status', Producto::ACTIVO)
                ->where('categoria_id', $request->input('id'))
                ->get();

            return response()->json(['data' => $circuitos], 200);
        }
    }



    /**
     * Obtener el costo de la inscripcion
     */
    public function userCosto(Request $request)
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

    /**
     * Mostrar stock de tallas
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTallaStock(Request $request)
    {
        if ($request->ajax()) {

            $talla = Talla::where('status', Talla::ACTIVO)
                ->where('id', $request->input('talla_id'))
                ->first();

            $talla ? $stock = $talla->stock : $stock = 0;

            return response()->json(['data' => $stock], 200);
        }

    }


}
