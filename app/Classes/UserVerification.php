<?php

/**
 * Clase utilizada como Facade automatico para utilizar sus metodos en la verificaion de la cuenta de usuario
 *
 * Para que funcione como Facade, al llamarla en un controlador o cualkier lugar anteponer a su namespace Facades
 *Ej: Facades\App\Classes\UserVerification
 * Ademas agregar un alias al app.php
 *
 * 'UserVerification'=>'Facades\App\Classes\UserVerification'
 *
 */

namespace App\Classes;

use App\Mail\UserCreated;
use App\Mail\UserUpdated;
use App\Traits\RedirectsUsers;
use App\User;
use Session;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Exceptions\UserVerification\TokenMismatchException;
use App\Exceptions\UserVerification\UserHasNoEmailException;
use App\Exceptions\UserVerification\UserIsVerifiedException;
use App\Exceptions\UserVerification\UserNotFoundException;

class UserVerification
{
    use RedirectsUsers;
    /**
     *
     * Generar y salvar token de verificacion del usuario y verified=false.
     *
     * @param AuthenticatableContract $user
     * @return UserVerification|bool
     */
    public function generate(User $user)
    {
        if (empty($user->email)) {
            $notification = [
                'message_toastr' => 'El usuario tiene un campo de correo nulo o vacio.',
                'alert-type' => 'info'];
            return redirect()->back()->with($notification);
//            return redirect()->back()->withAlert($message);
//            throw new UserHasNoEmailException();
        }

        return $this->saveToken($user, $this->generateToken());
    }


    /**
     * Generar token de verificacion
     *
     * @return string|bool
     */
    protected function generateToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    /**
     * Actualizar y salvar al usuario con el token de verificacion.
     * @param User $user
     * @param $token
     * @return bool
     */

    protected function saveToken(User $user, $token)
    {
        $user->verified = false;

        $user->verification_token = $token;

        return $user->save();
    }

    /**
     * Enviar por correo el link con el token de verificacion.
     * @param User $user
     */
    public function sendEmailVerification(User $user)
    {
        Mail::to($user)->send(new UserCreated($user));
    }

    /**
     * Enviar por correo el link con el token de verificacion para actualizacion de correo
     * @param User $user
     */
    public function sendEmailUpdateVerification(User $user)
    {
        Mail::to($user)->send(new UserUpdated($user));
    }

    /**
     * Procesar el email y el token recibidos desde el link del correo del usuario
     *
     * @param  string  $email
     * @param  string  $token
     * @return stdClass
     */
    public function process($email, $token)
    {
        $user = $this->getUserByEmail($email);

        unset($user->{"password"});

        // Verificar si el usuario se encuentra verificado.
        // Si es asi paro aki.
        $this->isVerified($user);

        //comparar teken almacenado y el del request
        $this->verifyToken($user->verification_token, $token);

        //validar la cuenta de usuario
        $this->wasVerified($user);

        return $user;
    }

    /**
     * Obtener el usuario por su email.
     * @param $email
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function getUserByEmail($email)
    {
        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        if ($user === null ) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * Chequear si el usuario esta verificado.
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function isVerified($user)
    {
        if ($user->verified == true) {

            throw new UserIsVerifiedException();
        }

    }

    /**
     * Comparar los dos tokens el almacenado y el que viene en el request del email del usuario
     * @param $storedToken
     * @param $requestToken
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function verifyToken($storedToken, $requestToken)
    {
        if ($storedToken != $requestToken) {

            throw new TokenMismatchException();
        }
    }

    /**
     * Actualizar y salvar al usuario como verificado.
     *
     * @param  stdClass  $user
     * @return void
     */
    protected function wasVerified($user)
    {
        $user->verification_token = null;

        $user->verified = true;

        $this->updateUser($user);

//        event(new UserVerified($user));
    }

    /**
     * Actualizar y salvar el objeto usuario.
     *
     * @param  stdClass  $user
     * @return void
     */
    protected function updateUser($user)
    {
        DB::table('users')
            ->where('email', $user->email)
            ->update([
                'verification_token' => $user->verification_token,
                'verified' => $user->verified
            ]);
    }
}
