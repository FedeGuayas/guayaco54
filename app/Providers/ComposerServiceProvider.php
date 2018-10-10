<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //Clase que se encargue de todos los métodos y funciones que devuelven los valores que deseamos a la vista
        //['welcome','layouts.back.master'] son las vistas o plantillas que utilizaran la clase
        View::composer(['welcome','layouts.back.master'], 'App\Http\ViewComposers\UserPendienteComposer');
        View::composer(['*'], 'App\Http\ViewComposers\ReservasPendientesComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
