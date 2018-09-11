<?php

namespace App\Exceptions\UserVerification;

use Exception;

class UserHasNoEmailException extends Exception
{
    protected $message = 'El usuario tiene un campo de correo nulo o vacio.';
}
