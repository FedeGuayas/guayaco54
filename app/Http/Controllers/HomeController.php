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

        $perfil=false;
        if (isset($user->persona)) {//no tiene perfil, debe crearlo antes de inscribirse
           $perfil=true;
        }

        return view('home',compact('perfil'));
    }

    public function getWelcome()
    {
        $config = Configuracion::where('status', Configuracion::ATIVO)->first();
        return view('welcome', compact('config'));
    }

}
