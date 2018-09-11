<?php

/**
 * Trait para gestionar la validacion de la cuenta al dar click en el link del email de verificaion
 */

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Facades\App\Classes\UserVerification;

use App\Exceptions\UserVerification\UserNotFoundException;
use App\Exceptions\UserVerification\UserIsVerifiedException;
use App\Exceptions\UserVerification\TokenMismatchException;

trait VerifiesUsers
{
    use RedirectsUsers;

    /**
     * Para permitir autologin si es true despues de verificar la cuenta
     * @var bool
     */
    protected $autologin = false;

    /**
     * Nombre de la vista retornada por el metodo getVerificationErrorView
     * @var string
     */
    protected $verificationErrorView = 'errors::user-verification-error';

    /**
     * Nombre de la vista para el email
     * @var string
     */
    protected $verificationEmailView = 'emails.users.verification::email-markdown';

    /**
     * Comienzo del manejo de la verificacion del usuario.
     * Metodo llamado al dar click en el link del email de verificacion de cuenta
     *
     * @param  string $token
     * @return \Illuminate\Http\Response
     */
    public function getVerification(Request $request, $token)
    {
        //validar que el request traiga una direccion de correo valida
        if (!$this->validateRequest($request)) {
            $message='El formato del correo no es correcto';
            return redirect($this->redirectIfVerificationFails($message));
        }

        try {

            //Procesar el email y el token recibidos desde el link del correo del usuario, en el facade
            $user = UserVerification::process($request->input('email'), $token);

        } catch (UserNotFoundException $e) {
            return redirect($this->redirectIfVerificationFails($e->getMessage()));
        } catch (UserIsVerifiedException $e) {
            $notification = [
                'message_toastr' => $e->getMessage(),
                'alert-type' => 'success'];
            return redirect()->route('login')->with($notification);
//            return redirect()->$this->redirectIfVerified($e->getMessage());
        } catch (TokenMismatchException $e) {
            return redirect($this->redirectIfVerificationFails($e->getMessage()));
        }

        if ($this->autologin === true) {
            auth()->loginUsingId($user->id);
        }

        return redirect($this->redirectAfterVerification());
    }

    /**
     * Mostrar vista de error si la verificacion falla.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVerificationError($message)
    {
        return view('errors.user-verification-error',compact('message'));

    }

    /**
     * Validar el link de verificacion, que traiga una direccion de correo valida.
     *
     * @param  string $token
     * @return bool
     */
    protected function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        return $validator->passes();
    }

    /**
     * Obtener nombre de la vista de verificacion de error
     *
     * @return string
     */
    protected function verificationErrorView()
    {
        return property_exists($this, 'verificationErrorView') ? $this->verificationErrorView : 'errors::user-verification-error';
    }

    /**
     * Obtener la vista del e-mail de verificacion.
     *
     * @return string
     */
    protected function verificationEmailView()
    {
        return property_exists($this, 'verificationEmailView') ? $this->verificationEmailView : 'emails.users.verification::email-markdown';
    }

}
