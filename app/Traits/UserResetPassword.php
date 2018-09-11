<?php
/**
 * Este trait es para sobrescribir el metodo para el envio del email de resetear contraseña
 */
namespace App\Traits;
use App\Notifications\UserResetPasswordNotification;

trait UserResetPassword
{
    /**
     * @param $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }
}
