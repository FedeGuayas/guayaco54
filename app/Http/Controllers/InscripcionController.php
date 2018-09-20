<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Configuracion;
use App\Deporte;
use App\Descuento;
use App\Factura;
use App\Inscripcion;
use App\Mpago;
use App\Persona;
use App\Producto;
use App\Talla;
use Carbon\Carbon;
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
     * Muestra el formulario para incripcion a un cliente backend
     *
     * @return \Illuminate\Http\Response
     */
    public function createBack(Request $request, Persona $persona)
    {
        $user = $request->user();

        $edad = $persona->getEdad();

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

        $descuentos_all = Descuento::where('status', Descuento::ACTIVO)
            ->select(DB::raw('concat (porciento," % ",nombre) as nombre,id'))
            ->get();
        $descuentos = $descuentos_all->pluck('nombre', 'id');

        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');

        return view('inscripcion.interna.create', compact('categorias', 'tallas', 'deportes', 'persona', 'formas_pago', 'descuentos'));
    }


    /**ONLINE
     * Muestra el formulario para inscripcion online
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
            ->where('categoria', 'NOT LIKE', '%deport%')//en online no se tendra en cuenta la categoria Deportistas
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

        $mp = Mpago::where('status', Mpago::ACTIVO)->get();
        $formas_pago = $mp->pluck('nombre', 'id');

        $perfil = $user->persona;

        return view('inscripcion.online.create', compact('categorias', 'tallas', 'deportes', 'perfil', 'formas_pago'));
    }

    /**Guaradar Inscripcion BACKEND
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $user = $request->user();

        $ahora = Carbon::now();

//        $rules = [
//            'persona_id' => 'required',
//            'categoria_id' => 'required',
//            'circuito_id' => 'required',
//            'talla' => 'required',
//            'mpago' => 'required',
//            'costo' => 'required',
//            'nombres_fact' => 'required',
//            'apellidos_fact' => 'required',
//            'num_doc_fact' => 'required',
//            'email_fact' => 'required',
//            'telefono_fact' => 'required',
//            'direccion_fact' => 'required'
//            //  "nombres" =>
//            //  "apellidos" =>
//            //  "fecha_nac" =>
//            //  "edad" =>
//            //  "gen" =>
//            //  "num_doc" =>
//            //  "email" =>
//            //  "telefono" =>
//            //  "direccion" =>
//        ];
//
//        $messages = [
//            'escenario.required' => 'EL escenario es un campo requerido',
//            'escenario.unique' => 'EL nombre del escenario ya se encuentra en uso',
//            'escenario.max' => 'EL nombre del escenario es demasiado largo, no debe sobrepasar los 20 caracteres',
//        ];
//
//        $validator = Validator::make($request->all(), $rules, $messages);
//
//        if ($validator->fails()) {
//            $notification = [
//                'message_toastr' => $validator->errors()->first(),
//                'alert-type' => 'error'];
//            return back()->with($notification)->withInput();
//        }

        try {

            DB::beginTransaction();


            $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
                ->select('ejercicio_id')
                ->first();

            $persona_id = $request->input('persona_id');
            $persona = Persona::where('id', $persona_id)->first();

            $categoria_id = $request->input('categoria_id');
            $circuito_id = $request->input('circuito_id');
            $producto = Producto::where('categoria_id', $categoria_id)
                ->where('circuito_id', $circuito_id)
                ->where('ejercicio_id', $ejercicio->ejercicio_id)
                ->first();

            $talla_id = $request->input('talla');
            $talla = Talla::where('id', $talla_id)->where('status', Talla::ACTIVO)->first();

            $mpago_id = $request->input('$mpago');
            $mpago = Mpago::where('status', Mpago::ACTIVO)->where('id', $mpago_id)->first();

            //con descuento aplicado si lo hay
            $costo = number_format($request->input('costo'), 2, '.', '');
            $descuento = Descuento::where('status', Descuento::ACTIVO)
                ->where('id', $request->input('descuentos'))
                ->first();
            $desc = 0; //descuento aplicado
            if ($descuento && $costo !== $producto->price) {
                $desc = ($producto->price) - ($costo);
            }


            $escenario_id = $user->escenario_id;


            $deporte = Deporte::where('id', $request->input('deporte_id'))->where('status', Deporte::ACTIVO)->first();


            //$nexNum => numero de factura
            $maxnumFact = DB::table('facturas')->max('numero'); //maximo valor en la columna numero
            if (is_numeric($maxnumFact)) {
                $nextNum = $maxnumFact + 1;
            } else {
                $maxnumFact = 0;
                $nextNum = 1;
            }

            $factura = new  Factura();
            $factura->numero = $nextNum;
            $factura->fecha_edit = $ahora; //Activa, cancelada
            $factura->descuento = $desc;
            $factura->subtotal=$producto->price;
            $factura->total=


//            $factura->subtotal=$;
            $factura->save();


            // nombres_fact
//apellidos_fact
//num_doc_fact
//email_fact
//telefono_fact
//direccion_fact
            $factura->descuento =
                $factura->save();

//            $deporte ? $inscripcion->factura_id=NULL: $inscripcion->factura()->associate($factura);
            $factura->increment('numero');


            $inscripcion = new Inscripcion();
            $inscripcion->escenario_id = $escenario_id;
            $inscripcion->producto()->associate($producto);
            $inscripcion->persona()->associate($persona);
            $inscripcion->user_id = $user->id;
            $inscripcion->user_edit = NULL;
            $deporte ? $inscripcion->deporte_id = $deporte->id : $inscripcion->deporte_id = NULL;
            $inscripcion->fecha = $ahora;
            $inscripcion->num_corredor = NULL;
            $inscripcion->kit = NULL;
            $inscripcion->num_corredor = NULL;
            $talla ? $inscripcion->talla()->associate($talla) : $inscripcion->talla_id = NULL;
            $inscripcion->costo = $request->input('costo');
            $inscripcion->ejercicio_id = $ejercicio_id;
            $inscripcion->status = Inscripcion::PAGADA;
            $inscripcion->save();


            DB::Commit();

        }catch (\Exception $e){
            $message=$e->getMessage();
            DB::rollBack();
        }


}

/**
 * Display the specified resource.
 *
 * @param  \App\Inscripcion $inscripcion
 * @return \Illuminate\Http\Response
 */
public
function show(Inscripcion $inscripcion)
{
    //
}

/**
 * Show the form for editing the specified resource.
 *
 * @param  \App\Inscripcion $inscripcion
 * @return \Illuminate\Http\Response
 */
public
function edit(Inscripcion $inscripcion)
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
public
function update(Request $request, Inscripcion $inscripcion)
{
    //
}

/**
 * Remove the specified resource from storage.
 *
 * @param  \App\Inscripcion $inscripcion
 * @return \Illuminate\Http\Response
 */
public
function destroy(Inscripcion $inscripcion)
{
    //
}

/**ONLINE
 * en este caso no se tienen en cuenta los deportes
 * Obtener lo circuitos para la categoia seleccionada
 */
public
function getCategoriaCircuito(Request $request)
{
    if ($request->ajax()) {

        $circuitos = Producto::with('circuito')
            ->where('status', Producto::ACTIVO)
            ->where('categoria_id', $request->input('id'))
            ->get();

        return response()->json(['data' => $circuitos], 200);
    }
}

/**Para Backend
 *
 * Obtener lo circuitos para la categoia seleccionada
 */
public
function getCatCir(Request $request)
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

/**BACKEND
 * Obtener el costo de la inscripcion,  tener en cuenta los descuentos
 */
public
function getCosto(Request $request)
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

        $descuento = Descuento::where('status', Descuento::ACTIVO)
            ->where('id', $request->input('descuento_id'))
            ->first();

        if ($descuento) {
            $descuento = $descuento->appDescuento($costo);
            $costo = $costo - $descuento;
            $costo = number_format($costo, 2, '.', ' ');
        }

        return response()->json(['data' => $costo], 200);
    }
}


/**ONLINE
 * Obtener el costo de la inscripcion
 */
public
function userOnlineCosto(Request $request)
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

/**ONLINE
 * Mostrar stock de tallas
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public
function getTallaStock(Request $request)
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
