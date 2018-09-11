<?php

namespace App\Http\Controllers;

use App\Asociado;
use Facades\App\Classes\LogActivity;
use App\Persona;
use App\Role;
use App\User;
use function Couchbase\defaultEncoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    public function __construct()
    {
//        setlocale(LC_TIME, 'es_ES.utf8'); //en el vps
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     *Crear nuevo perfil de usuario asignar el nuevo rol de cliente
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //si se selecciono un perfil entonces actualizarlo
        $persona_id=$request->input('persona_id',null);
        if ($persona_id) {
            $persona=Persona::where('id',$persona_id)->first();
            return $this->update($request,$persona);
        }

        $user=$request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc',
            'email' => 'nullable|email',
            'direccion' => 'required',
            'discapacitado' => 'required',
            'privado' => 'required'
        ];

        $messages = [
            'nombres.required' => 'El nombre es  requerido',
            'apellidos.required' => 'El apellido es  requerido',
            'fecha_nac.required' => 'La fecha de nacimiento es  requerida',
            'gen.required' => 'El género es  requerido',
            'num_doc.required'=>'El número de identificación es requerido',
            'num_doc.unique'=>'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email'=>'El formato del email es incorrecto',
            'direccion.required' => 'La dirección es  requerida',
            'discapacitado.required' => 'Debe seleccionar si tiene discapacidad o no',
            'privado.required' => 'Debe seleccionar si su perfil será privado o no',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
//              return back()->withErrors($validator)->withInput();
        }


        try {
            DB::beginTransaction();

            $discapacitado=$request->input('discapacitado'); //no,si
            $privado=$request->input('privado');//no,si

            $persona=new Persona;
            $persona->nombres=$request->input('nombres');
            $persona->apellidos=$request->input('apellidos');
            $persona->num_doc=$request->input('num_doc');
            $persona->gen=$request->input('gen');
            if ($discapacitado==='si'){
                $persona->discapacitado=Persona::DISCAPACITADO;
            }elseif ($discapacitado==='no'){
                $persona->discapacitado=Persona::NO_DISCAPACITADO;
            }
            $persona->fecha_nac=$request->input('fecha_nac');
            $persona->email=$request->input('email');
            $persona->direccion=$request->input('direccion');
            $persona->telefono=$request->input('telefono');
            if ($privado==='si'){
                $persona->privado=Persona::PERFIL_PRIVADO;
            }elseif ($privado==='no'){
                $persona->privado=Persona::PERFIL_PUBLICO;
            }
            $persona->estado=Persona::PERFIL_ACTIVO;

            $persona->save();

            //vinculo el perfil al usuario logueado
            $user->persona()->associate($persona);

            $role_client=Role::findByName('client');
            //verifico si el usuario no tenia el rol de cliente
            if (!$user->hasRole($role_client->name)){
                // y se lo asigno
                $user->assignRole($role_client->name);
            }

            $user->update();

            //guardo el log
            LogActivity::addToLog('Perfil creado',$user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Su Perfil fue creado correctamente.',
                'alert-type' => 'info'];
            return redirect()->route('getProfile')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
//            $message=$e->getMessage();
            $message='Lo sentimos! Ocurrio un error y no se pudo crear su perfil, intentelo de nuevo.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Actualizar perfil existente del usuario logueado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Persona $persona)
    {
        $user=$request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc,'. $persona->id,
            'email' => 'nullable|email',
            'direccion' => 'required',
            'discapacitado' => 'required',
            'privado' => 'required'
        ];

        $messages = [
            'nombres.required' => 'El nombre es  requerido',
            'apellidos.required' => 'El apellido es  requerido',
            'fecha_nac.required' => 'La fecha de nacimiento es  requerida',
            'gen.required' => 'El género es  requerido',
            'num_doc.required'=>'El número de identificación es requerido',
            'num_doc.unique'=>'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email'=>'El formato del email es incorrecto',
            'direccion.required' => 'La dirección es  requerida',
            'discapacitado.required' => 'Debe seleccionar si tiene discapacidad o no',
            'privado.required' => 'Debe seleccionar si su perfil será privado o no',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        try {
            DB::beginTransaction();

            $discapacitado=$request->input('discapacitado'); //no,si
            $privado=$request->input('privado');//no,si

            $persona->nombres=$request->input('nombres');
            $persona->apellidos=$request->input('apellidos');
            $persona->num_doc=$request->input('num_doc');
            $persona->gen=$request->input('gen');
            if ($discapacitado==='si'){
                $persona->discapacitado=Persona::DISCAPACITADO;
            }elseif ($discapacitado==='no'){
                $persona->discapacitado=Persona::NO_DISCAPACITADO;
            }
            $persona->fecha_nac=$request->input('fecha_nac');
            $persona->email=$request->input('email');
            $persona->direccion=$request->input('direccion');
            $persona->telefono=$request->input('telefono');
            if ($privado==='si'){
                $persona->privado=Persona::PERFIL_PRIVADO;
            }elseif ($privado==='no'){
                $persona->privado=Persona::PERFIL_PUBLICO;
            }
            $persona->estado=Persona::PERFIL_ACTIVO;

            $persona->update();

            //Solo entra la primera vez que edita su perfil si lo encontro en la bbdd y no tenia un perfil asociado a su cuenta
            if (!count($user->persona)) { //la cuenta no tiene perfil asociado
                //vinculo el perfil al usuario logueado
                $user->persona()->associate($persona);

                $role_client=Role::findByName('client');
                //verifico si el usuario no tenia el rol de cliente
                if (!$user->hasRole($role_client->name)){
                    // y se lo asigno
                    $user->assignRole($role_client->name);
                }

                $user->update();
            }

            //guardo el log
            LogActivity::addToLog('Perfil actualizado',$user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Su Perfil fue actualizado correctamente.',
                'alert-type' => 'success'];
            return redirect()->route('getProfile')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
//            $message=$e->getMessage();
            $message='Lo sentimos! Ocurrio un error y no se pudo actualizar su perfil, intentelo de nuevo.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     *Guardar  nuevo perfil asociado y asociarlo a cuenta de usuario logueado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAsociado(Request $request)
    {
        $user=$request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc',
            'email' => 'nullable|email',
            'direccion' => 'required',
            'discapacitado' => 'required',
        ];

        $messages = [
            'nombres.required' => 'El nombre es  requerido',
            'apellidos.required' => 'El apellido es  requerido',
            'fecha_nac.required' => 'La fecha de nacimiento es  requerida',
            'gen.required' => 'El género es  requerido',
            'num_doc.required'=>'El número de identificación es requerido',
            'num_doc.unique'=>'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email'=>'El formato del email es incorrecto',
            'direccion.required' => 'La dirección es  requerida',
            'discapacitado.required' => 'Debe seleccionar si tiene discapacidad o no',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
//              return back()->withErrors($validator)->withInput();
        }


        try {
            DB::beginTransaction();

            $discapacitado = $request->input('discapacitado'); //no,si
            $privado = $request->input('privado');//no,si

            $persona = new Persona;
            $persona->nombres = $request->input('nombres');
            $persona->apellidos = $request->input('apellidos');
            $persona->num_doc = $request->input('num_doc');
            $persona->gen = $request->input('gen');
            if ($discapacitado === 'si') {
                $persona->discapacitado = Persona::DISCAPACITADO;
            } elseif ($discapacitado === 'no') {
                $persona->discapacitado = Persona::NO_DISCAPACITADO;
            }
            $persona->fecha_nac = $request->input('fecha_nac');
            $persona->email = $request->input('email');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->privado = Persona::PERFIL_PUBLICO;
            $persona->estado = Persona::PERFIL_ACTIVO;

            $persona->save();

            //vinculo el perfil asociado a los asociados del usuario logueado
            $asociado = new Asociado();
            $asociado->persona()->associate($persona);
            $asociado->user()->associate($user);
            $asociado->save();

            //guardo el log
            LogActivity::addToLog('Perfil nuevo asociado creado', $user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Se creo el perfil asociado correctamente.',
                'alert-type' => 'info'];
            return redirect()->route('getProfile')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
//            $message=$e->getMessage();
            $message='Lo sentimos! Ocurrio un error y no se pudo crear el perfil asociado, intentelo de nuevamente mas tarde.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     *Actualizar perfil asociado a cuenta de usuario logueado, solo sino tiene cuenta de usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAsociado(Request $request, Persona $persona)
    {
        $user=$request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc,'. $persona->id,
            'email' => 'nullable|email',
            'direccion' => 'required',
            'discapacitado' => 'required',
        ];

        $messages = [
            'nombres.required' => 'El nombre es  requerido',
            'apellidos.required' => 'El apellido es  requerido',
            'fecha_nac.required' => 'La fecha de nacimiento es  requerida',
            'gen.required' => 'El género es  requerido',
            'num_doc.required'=>'El número de identificación es requerido',
            'num_doc.unique'=>'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email'=>'El formato del email es incorrecto',
            'direccion.required' => 'La dirección es  requerida',
            'discapacitado.required' => 'Debe seleccionar si tiene discapacidad o no',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $existe=Asociado::where('user_id',$user->id)->where('persona_id',$persona->id)->first();

        if (!$existe){
            abort(403);
        }

        // Si el perfil que se desea editar tiene una cuenta de usuario asociada, o es privada
        if ((isset($persona->user) || $persona->privado == Persona::PERFIL_PRIVADO)) {
           abort(403);
        }

        try {
            DB::beginTransaction();

            $discapacitado = $request->input('discapacitado'); //no,si

            $persona->nombres = $request->input('nombres');
            $persona->apellidos = $request->input('apellidos');
            $persona->num_doc = $request->input('num_doc');
            $persona->gen = $request->input('gen');
            if ($discapacitado === 'si') {
                $persona->discapacitado = Persona::DISCAPACITADO;
            } elseif ($discapacitado === 'no') {
                $persona->discapacitado = Persona::NO_DISCAPACITADO;
            }
            $persona->fecha_nac = $request->input('fecha_nac');
            $persona->email = $request->input('email');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->privado = Persona::PERFIL_PUBLICO;
            $persona->estado = Persona::PERFIL_ACTIVO;

            $persona->update();

            //guardo el log
            LogActivity::addToLog('Perfil asociado actualizado', $user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Se actualizo el perfil asociado correctamente.',
                'alert-type' => 'success'];
            return redirect()->route('getProfile')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
//            $message=$e->getMessage();
            $message='Lo sentimos! Ocurrio un error y no se pudo actualizar el perfil asociado, intentelo de nuevamente mas tarde.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        //
    }
}
