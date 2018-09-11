<?php

namespace App\Http\Controllers\Auth;

use App\Classes\LogActivity;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Facades\App\Classes\UserVerification;
use App\Traits\VerifiesUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers; //Verificacion de cuenta de usuario

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
//            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //crear el name con la union del nombre y el apellido
//        $inicial=explode(' ',$data['first_name']);
//        $apellidos=explode(' ',$data['last_name']);
//        $name = title_case($inicial[0] . ' ' . $apellidos[0]);

        return User::create([
//            'name' => $name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    /**
     * Sobreescribir este metodo necesarion para el user verification
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        try {

            DB::beginTransaction();

            $user = $this->create($request->all());

            $role=Role::findByName('registered');

            $user->assignRole($role->name);

            event(new Registered($user));

            //logear al usuario al registrarse
//        $this->guard()->login($user);

            //generar y salvar el token de verificacion de email
            UserVerification::generate($user);

            //enviar email de notificacion al usuario
            UserVerification::sendEmailVerification($user);

            LogActivity::addToLog('Nuevo usuario registrado',$user);

            DB::commit();

            // return $this->registered($request, $user)
//            ?: redirect($this->redirectPath());
            $notification = [
                'message_toastr' => 'Registro satisfactorio, por favor verifique su email antes de iniciar sessión.',
                'alert-type' => 'info'];

            return redirect()->route('login')->with($notification);

        } catch (Exception $e) {
            DB::rollback();
//            $message=$e->getMessage();
            $message='Lo sentimos! Ocurrio un error durante el registro.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }


    }

    /**
     *
     * Deshabilitar el registro
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function showRegistrationForm()
//    {
//        return redirect()->route('login');
//    }

}
