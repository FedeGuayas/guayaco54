<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\UserVerified;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //solo usuarios verificados se pueden logear
//    protected function credentials(Request $request)
//    {
////        return $request->only($this->username(), 'password');
//        return [
//            'email' => $request->email,
//            'password' => $request->password,
//            'status' => UserVerification::isVerified(),
//       ];
//    }

    /**
     * Sobreescribir el metodo login para permitir solo los usuario verificados poder loguearse
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Esta parte es la que se modifico
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            // Verificar que el usuario este verificado
            if ($user->verified == true && $this->attemptLogin($request)) {
                //Enviar respuesta por defeecto si es true,  Send the normal successful login response
                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                $this->incrementLoginAttempts($request);
                $notification = [
                    'message_toastr' => 'Debe verificar su cuenta antes de iniciar sessión.',
                    'alert-type' => 'error'];
                return redirect()->back()->with($notification)
                    ->withInput($request->only($this->username(), 'remember'));
            }
        }

//        if ($this->attemptLogin($request)) {
//            return $this->sendLoginResponse($request);
//        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

}
