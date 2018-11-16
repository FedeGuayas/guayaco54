<?php

namespace App\Http\Controllers;

use App\Maintenance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Queue\Worker;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * MaintenanceController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trabajadores_all=User::role('employee')->with('persona')
            ->select(DB::raw('concat (first_name," ",last_name) as nombre,id'))
            ->get();

        $trabajadores=$trabajadores_all->pluck('nombre', 'id');

        $mantenimiento=Maintenance::where('id',1)->first();


        return view('mantenimiento.index',compact('trabajadores','mantenimiento'));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mantenimiento=Maintenance::where('id',1)->first();

        if ($request->status=='on') {
            $mantenimiento->status=Maintenance::APP_ON;
        }else{
            $mantenimiento->status=Maintenance::APP_OFF;
        }

        $mantenimiento->users_permit=$request->users_permit;

        $mantenimiento->save();

        return redirect()->route('maintenances.index');
    }

}
