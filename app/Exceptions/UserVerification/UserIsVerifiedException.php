<?php

namespace App\Exceptions\UserVerification;

use Exception;

class UserIsVerifiedException extends Exception
{
    protected $message = 'Esta cuenta de usuario ya se encuentra verificada. Inicie sessión';
}
