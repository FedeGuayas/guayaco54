<?php

namespace App\Exceptions\UserVerification;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'No se encontró usuario para la dirección de correo especificada.';
}
