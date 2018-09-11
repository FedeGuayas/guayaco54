<?php

namespace App\Http\Controllers;

use App\Asociado;
use Illuminate\Http\Request;

class AsociadoController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function show(Asociado $asociado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function edit(Asociado $asociado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asociado $asociado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asociado  $asociado
     * @return \Illuminate\Http\Response
     */
    public function destroy( Asociado $asociado)
    {

        $asociado->delete();

        return response()->json(['data'=>$asociado],200);

    }
}
