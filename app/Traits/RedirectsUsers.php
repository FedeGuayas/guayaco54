<?php
/**
 * A donde se redireccionara al usuario una vez procesada la verificacion
 * Este trait se utiliza dentro del trait VerifiesUser
 *
 * (c) Jean Ragouin <go@askjong.com> <www.askjong.com>
 */

namespace App\Traits;

trait RedirectsUsers
{
    /**
     * A donde redireccionar si el usurario autenticado esta verificado.
     * @var string
     */
//    protected $redirectIfVerified = '/';

    /**
     * A donde redireccionar despues que la verificacion del token de verificacion es correcta
     * @var string
     */
    protected $redirectAfterVerification = '/';

    /**
     * A donde redireccionar despues de fallar si falla la verificacion del token
     * @var string
     */
//    protected $redirectIfVerificationFails = '/email-verification/error';

    /**
     *  Obtener el redirect path si el usuario se encuentra verificado.
     *
     * @return string
     */
    public function redirectIfVerified($message)
    {
        return property_exists($this, 'redirectIfVerified') ? $this->redirectIfVerified : route('login');
    }

    /**
     *  Obtener el redirect path cuando la verificacion de token  es correcta.
     *
     * @return string
     */
    public function redirectAfterVerification()
    {
        return property_exists($this, 'redirectAfterVerification') ? $this->redirectAfterVerification : '/';
    }

    /**
     * Obtener el redirect path  cuando falla la verificacion del token.
     * Con esta ruta muetra la vista del error
     * @return string
     */
    public function redirectIfVerificationFails($message)
    {
        return property_exists($this, 'redirectIfVerificationFails') ? $this->redirectIfVerificationFails : route('email-verification.error',['message'=>$message]);
    }

}
