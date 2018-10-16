<?php

/**
 * Created by PhpStorm.
 * User: Programador2
 * Date: 10/10/2018
 * Time: 11:46
 *
 * Clase encargada de devolver los valores a la vista de los pagos pendientes al usuario logueado
 */

namespace  App\Http\ViewComposers;



use App\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConfiguracionComposer
{
    protected $user;


    public function __construct(Request $request)
    {

        $this->user = $request->user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if(Auth::check()) {
            $configuracion = Configuracion::where('status', Configuracion::ATIVO)->first();

            $view->with('configuracion', $configuracion);
        }

    }

}