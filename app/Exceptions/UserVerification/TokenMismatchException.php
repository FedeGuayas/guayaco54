<?php

namespace App\Exceptions\UserVerification;

use Exception;

class TokenMismatchException extends Exception
{
    protected $message = 'Token de verificación incorrecto.';
}
