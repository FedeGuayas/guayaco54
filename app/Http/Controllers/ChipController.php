<?php

namespace App\Http\Controllers;

use App\Chip;
use App\Configuracion;
use App\Inscripcion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChipController extends Controller
{
    /**
     * ChipController constructor.
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function show(Chip $chip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function edit(Chip $chip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chip $chip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chip  $chip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chip $chip)
    {
        //
    }


    public function inscripcionesExcelChip(Request $request)
    {
        $ejercicio = Configuracion::where('status', Configuracion::ATIVO)
            ->select('ejercicio_id')
            ->first();

        $reg_desde = $request->input('reg_desde');
        $reg_hasta = $request->input('reg_hasta');

        $inscripciones=Inscripcion::from('inscripcions as i')
            ->with('producto','persona','factura')
            ->whereBetween('i.id', [$reg_desde, $reg_hasta])
            ->where('ejercicio_id', $ejercicio->ejercicio_id)
            ->where('i.status',Inscripcion::PAGADA)
            ->get();

        $inscripcionesArray[] = ['CHIP', 'APELLIDOS', 'NOMBRES', 'CEDULA', 'FECHA DE NAC.', 'SEXO', 'EMAIL', 'TELEFONO', 'DIRECCION', 'CATEGORÍA', 'CIRCUITO'];
        foreach ($inscripciones as $insc) {

            $inscripcionesArray[] = [
                'numero' => $insc->num_corredor,
                'apellidos' => $insc->persona->apellidos,
                'nombres' => $insc->persona->nombres,
                'cedula' => $insc->persona->num_doc,
                'fecha_nac' => $insc->persona->fecha_nac,
//                'sexo' => $insc->genero == 'Masculino' ? 'M' : 'F',
                'sexo' => $insc->persona->gen,
                'email' => isset($insc->persona->email) ? $insc->persona->email : $insc->factura->email,
                'telefono' => isset($insc->persona->telefono) ? $insc->persona->telefono : $insc->factura->telefono,
                'direccion' => $insc->persona->direccion,
//                'edad' => $insc->edad,
                'categoria' => $insc->producto->categoria->categoria,
                'circuito' => $insc->producto->circuito->circuito,
            ];
        }

        Excel::create('Inscripciones Chips', function ($excel) use ($inscripcionesArray) {

            $excel->sheet('Chips', function ($sheet) use ($inscripcionesArray) {

                $sheet->cells('A1:K1', function ($cells) {
                    $cells->setFontWeight('bold');
                    //alineacion horizontal
                    $cells->setAlignment('center');
                    // alineacion vertical
                    $cells->setValignment('center');
                });

                $sheet->fromArray($inscripcionesArray, null, 'A1', false, false);

            });
        })->export('xlsx');
        return view('inscripcions.index');
    }
}
