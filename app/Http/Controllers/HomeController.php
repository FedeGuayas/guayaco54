<?php

namespace App\Http\Controllers;


use App\Configuracion;
use App\Factura;
use App\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perfil = false;
        if (isset($user->persona) || $user->hasRole('employee')) {//no tiene perfil, debe crearlo antes de inscribirse , al empleado no pedir crearlo
            $perfil = true;
        }

        $insc_pagar = Inscripcion::from('inscripcions as i')
            ->join('facturas as f', 'f.id', '=', 'i.factura_id')
            ->where('i.user_online', $user->id)
            ->where('i.status', Inscripcion::RESERVADA)
            ->where('i.inscripcion_type', Inscripcion::INSCRIPCION_ONLINE)
            ->where('f.status', Factura::PENDIENTE)
            ->get();

        Session::put('insc_pagar', $insc_pagar->count());

        return view('home', compact('perfil'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWelcome(Request $request)
    {
        $user = $request->user();
        $config = Configuracion::where('status', Configuracion::ATIVO)->first();
        //buscar inscripciones pendientes
        $insc_pagar = Inscripcion::from('inscripcions as i')
            ->join('facturas as f', 'f.id', '=', 'i.factura_id')
            ->where('i.user_online', $user->id)
            ->where('i.status', Inscripcion::RESERVADA)
            ->where('i.inscripcion_type', Inscripcion::INSCRIPCION_ONLINE)
            ->where('f.status', Factura::PENDIENTE)
            ->get();

        Session::put('insc_pagar', $insc_pagar->count());
        return view('welcome', compact('config'));
    }

    public function getTerms()
    {
        return view('inscripcion.online.terminos');
    }


}
