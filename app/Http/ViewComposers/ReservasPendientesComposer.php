<?php
/**
 * Created by PhpStorm.
 * User: Programador2
 * Date: 10/10/2018
 * Time: 12:24
 *
 * Composer para mostrar la cantidad de reservas pendientes en el backend de los trabajadores
 */

namespace App\Http\ViewComposers;


use App\Inscripcion;
use Illuminate\View\View;

class ReservasPendientesComposer
{


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $inscripciones = Inscripcion::
        with('user', 'producto', 'persona', 'talla', 'factura')
            ->where('inscripcions.status', '=', Inscripcion::RESERVADA)
            ->get();
        $view->with('cantidad_reservas', $inscripciones->count());
    }

}