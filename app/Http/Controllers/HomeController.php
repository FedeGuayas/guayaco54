<?php

namespace App\Http\Controllers;


use App\Configuracion;
use Illuminate\Http\Request;

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

//        $perfil = false;
//        if (isset($user->persona) || $user->hasRole('employee')) {//no tiene perfil, debe crearlo antes de inscribirse , al empleado no pedir crearlo
//            $perfil = true;
//        }

        if ($user->hasRole('employee')) {
            return redirect()->route('personas.index');
        } else
            return redirect()->route('getProfile');

//            return view('home', compact('perfil'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWelcome()
    {
        $config = Configuracion::where('status', Configuracion::ATIVO)->first();

        return view('welcome', compact('config'));
    }

    public function getTerms()
    {
        return view('inscripcion.online.terminos');
    }

    public function getReglamento()
    {
        return view('inscripcion.online.reglamento');
    }


}
