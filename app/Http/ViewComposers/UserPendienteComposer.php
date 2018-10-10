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



use App\Factura;
use App\Inscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserPendienteComposer
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
            $insc_pagar = Inscripcion::from('inscripcions as i')
                ->join('facturas as f', 'f.id', '=', 'i.factura_id')
                ->where('i.user_online', $this->user->id)
                ->where('i.status', Inscripcion::RESERVADA)
                ->where('i.inscripcion_type', Inscripcion::INSCRIPCION_ONLINE)
                ->where('f.status', Factura::PENDIENTE)
                ->get();

            $view->with('insc_pagar', $insc_pagar->count());
        }

    }

}