<?php
/**
 * Este trait es para comprobar si el usuario esta verificado o no
 * Se utiliza en el model User
 */
namespace App\Traits;

trait UserVerified
{
    /**
     *  Chequear si el usuario esta verificado.
     *
     *  Si verified=1 return true
     *
     * @return boolean
     */
    public function isVerified()
    {
        return (bool) $this->verified;
    }

    /**
     * Chequear si la verificacion esta pendiente.
     *
     * Si verified=0 y verification_token!=null return true
     *
     * @return boolean
     */
    public function isPendingVerification()
    {
        return ! $this->isVerified() && $this->hasVerificationToken();
    }

    /**
     * Chequear si el usuario tiene un token de verificacion.
     *
     *  verification_token!=null
     *
     * @return bool
     */
    public function hasVerificationToken()
    {
        return ! is_null($this->verification_token);
    }
}
