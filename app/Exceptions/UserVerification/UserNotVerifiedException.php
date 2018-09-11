<?php

namespace App\Exceptions\UserVerification;

use Exception;

class UserNotVerifiedException extends Exception
{
    protected $message = 'El usuario no se ha verificado.';


}
