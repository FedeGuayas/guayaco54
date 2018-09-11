<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;


class LogAuthenticationAttempt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     *
     * Handle the event.
     *
     * @param  Attempting  $event
     * @return void
     */
    public function handle(Attempting $event)
    {
        //credenciales del usuario: email y correo
//        dd($event->credentials);
//        $event->credentials=[
//            "email" => "dulce02@example.com"
//            "password" => "secret"];

    }
}
