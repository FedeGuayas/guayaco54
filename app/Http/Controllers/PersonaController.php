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
use Yajra\Datatables\Datatables;

class PersonaController extends Controller
{
    public function __construct()
    {
//        setlocale(LC_TIME, 'es_ES.utf8'); //en el vps
        $this->middleware('auth');

    }

    /**
     * Mostra todos los perfiles existentes
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->can('view_personas')) {

            return view('personas.back.index');

        } else abort(403);

    }


    /**
     * Usuarios que no son trabajadores (role!=employee),  ajax
     */
    public function getAllPersonas(Request $request)
    {

        $user = $request->user();

        if ($user->can('view_personas')) {

            if ($request->ajax()) {

                $personas = Persona::

                with('user', 'inscripciones')
                    ->where('estado', Persona::PERFIL_ACTIVO)
//                ->leftJoin('users','personas.id','=','users.persona_id')
//                ->where('first_name','!=','admin') //no mostrar el admin
//                ->whereHas('roles', function($q){ //con rol=employee
//                    $q->where('name', '=', 'employee');
//                })
//                ->whereDoesntHave('roles', function($query) { //que el rol no sea employee
//                    $query->where('name', '=', 'employee');
//                })
                    ->select('personas.*');

                $action_buttons = '
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                <div class="dropdown-menu dropdown-menu-left">
                 @can(\'add_inscripciones\')
                    <a class="dropdown-item" href="{{ route(\'inscriptions.create\',[$id]) }}">
                        <i class="fa fa-check-square-o text-primary"></i>Inscribir
                    </a>
                @endcan
                @can(\'edit_personas\')
                    <a class="dropdown-item" href="{{ route(\'personas.edit\',[$id]) }}">
                        <i class="fa fa-pencil text-success"></i>Editar
                    </a>
                @endcan
                @can(\'delete_personas\')
                    <a class="dropdown-item delete" href="#" data-id="{{$id}}">
                        <i class="fa fa-trash-o text-danger"></i> Eliminar
                    </a>
                @endcan
                </div>
            </div>
                ';

                $datatable = Datatables::of($personas)
                    ->addColumn('actions', $action_buttons)
//                ->addColumn('nombres', function ($usuario) {
//                    return $usuario->getFullName();
//                })

//                ->addColumn('role', function ($usuario) {
//                    return $usuario->getRoleNames();
//                })
//                ->filterColumn('nombres', function ($query, $keyword) {
//                    $query->whereRaw("CONCAT(users.first_name,' ',users.last_name) like ?", ["%{$keyword}%"]);
//                })
                    ->rawColumns(['actions'])
                    ->setRowId('id');
                //Agregar variables a a la respuesta json del datatables
                if ($request->draw == 1) {
                    $datatable->with([
                        'generos' => ['M','F']
                    ]);
                }

                return $datatable->make(true);

            }

        } else abort(403);

    }

    /**
     *Crear personas (perfiles) internamente por los trabajadores
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();

        if ($user->can('add_personas')) {

            return view('personas.back.create');

        } else abort(403);

    }

    /**
     * Cliente creado por trabajador
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function storeBack(Request $request)
    {
        $user = $request->user();

        if ($user->can('add_personas')) {

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
                'num_doc.required' => 'El número de identificación es requerido',
                'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
                'email.email' => 'El formato del email es incorrecto',
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

                $this->personaSave($request);

                //guardo el log
                LogActivity::addToLog('Cliente creado por trabajador', $user);

                DB::commit();
                $notification = [
                    'message_toastr' => 'Cliente creado correctamente.',
                    'alert-type' => 'success'];
                return redirect()->route('personas.index')->with($notification);

            } catch (\Exception $e) {
                DB::rollback();
//            $message=$e->getMessage();
                $message = 'Lo sentimos! Ocurrio un error y no se pudo crear el cliente.';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->back()->with($notification)->withInput();

            }
        }else abort(403);

    }

    /**
     * ONLINE
     * A este metodo accese el usuario al intentar crear su perfil por primera vez o si selecciona uno existen lo actualiza
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //si se selecciono un perfil entonces actualizarlo
        $persona_id = $request->input('persona_id', null);
        if ($persona_id) {
            $persona = Persona::where('id', $persona_id)->first();
            return $this->update($request, $persona);
        }

        $user = $request->user();

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
            'num_doc.required' => 'El número de identificación es requerido',
            'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email' => 'El formato del email es incorrecto',
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

            $persona = $this->personaSave($request);

            //vinculo el perfil al usuario logueado si no lo esta creando un trabajador
            $user->persona()->associate($persona);

            $role_client = Role::findByName('client');
            //verifico si el usuario no tenia el rol de cliente
            if (!$user->hasRole($role_client->name)) {
                // y se lo asigno
                $user->assignRole($role_client->name);
            }

            $user->update();

            //guardo el log
            LogActivity::addToLog('Perfil creado', $user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Perfil creado correctamente.',
                'alert-type' => 'info'];
            return redirect()->route('getProfile')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
//            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error y no se pudo crear su perfil, intentelo de nuevo.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     * Metodo para guardar a la persona forn y back
     * @param $request
     * @return Persona
     */
    private function personaSave($request)
    {

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
        if ($privado === 'si') {
            $persona->privado = Persona::PERFIL_PRIVADO;
        } elseif ($privado === 'no') {
            $persona->privado = Persona::PERFIL_PUBLICO;
        }
        $persona->estado = Persona::PERFIL_ACTIVO;

        $persona->save();

        return $persona;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Persona $persona
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        //
    }

    /**
     * Metodo para editar las personas en el backend por parte de los trabajadores
     *
     * @param  \App\Persona $persona
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Persona $persona)
    {
        if ($request->user()->can('edit_personas')){

            return view('personas.back.edit',compact('persona'));

        }else abort(403);


    }

    /**
     * Actualizar el cliente por el trabajador
     *
     * @param Request $request
     * @param Persona $persona
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateBack(Request $request, Persona $persona)
    {
        $user = $request->user();

        if ($user->can('edit_personas')) {

            $rules = [
                'nombres' => 'required',
                'apellidos' => 'required',
                'fecha_nac' => 'required',
                'gen' => 'required',
                'num_doc' => 'required|unique:personas,num_doc,' . $persona->id,
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
                'num_doc.required' => 'El número de identificación es requerido',
                'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
                'email.email' => 'El formato del email es incorrecto',
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

                $this->personaUpdate($request, $persona);

                //guardo el log
                LogActivity::addToLog('Cliente actualizado por trabajador', $user);

                DB::commit();
                $notification = [
                    'message_toastr' => 'Cliente actualizado correctamente.',
                    'alert-type' => 'success'];
                return redirect()->route('personas.index')->with($notification);

            } catch (\Exception $e) {
                DB::rollback();
//            $message=$e->getMessage();
                $message = 'Lo sentimos! Ocurrio un error y no se pudo actualizar el cliente.';
                $notification = [
                    'message_toastr' => $message,
                    'alert-type' => 'error'];
                return redirect()->back()->with($notification)->withInput();
            }
        }else abort(403);

    }

    /**
     * ONLINE
     * Actualizar perfil existente del usuario logueado
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Persona $persona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Persona $persona)
    {
        $user = $request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc,' . $persona->id,
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
            'num_doc.required' => 'El número de identificación es requerido',
            'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email' => 'El formato del email es incorrecto',
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

            $persona = $this->personaUpdate($request, $persona);

            //Solo entra la primera vez que edita su perfil si lo encontro en la bbdd y no tenia un perfil asociado a su cuenta
            if (!count($user->persona)) { //la cuenta no tiene perfil asociado
                //vinculo el perfil al usuario logueado
                $user->persona()->associate($persona);

                $role_client = Role::findByName('client');
                //verifico si el usuario no tenia el rol de cliente
                if (!$user->hasRole($role_client->name)) {
                    // y se lo asigno
                    $user->assignRole($role_client->name);
                }

                $user->update();
            }

            //guardo el log
            LogActivity::addToLog('Perfil actualizado', $user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Perfil actualizado correctamente.',
                'alert-type' => 'success'];
            return redirect()->route('getProfile')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
//            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error y no se pudo actualizar el perfil, intentelo de nuevo.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }


    /**Actualizar a la persona
     * @param $request
     * @param $persona
     * @return mixed
     */
    private function personaUpdate($request, $persona)
    {
        $discapacitado = $request->input('discapacitado'); //no,si
        $privado = $request->input('privado');//no,si

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
        if ($privado === 'si') {
            $persona->privado = Persona::PERFIL_PRIVADO;
        } elseif ($privado === 'no') {
            $persona->privado = Persona::PERFIL_PUBLICO;
        }
        $persona->estado = Persona::PERFIL_ACTIVO;

        $persona->update();

        return $persona;
    }


    /**
     * ONLINE
     * Este metodo es cuando un usuario online crea un perfil a un amigo y lo quiere asociar a su cuenta para poder inscribirlo
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeAsociado(Request $request)
    {
        $user = $request->user();

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
            'num_doc.required' => 'El número de identificación es requerido',
            'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email' => 'El formato del email es incorrecto',
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

            //vinculo el perfil asociado a los nuevos asociados del usuario logueado
            $asociado = new Asociado();
            $asociado->persona()->associate($persona);
            $asociado->user()->associate($user);
            $asociado->save();

            //guardo el log
            LogActivity::addToLog('Creado nuevo Perfil Asociado', $user);

            DB::commit();
            $notification = [
                'message_toastr' => 'Se creo el perfil asociado correctamente.',
                'alert-type' => 'info'];
            return redirect()->route('getProfile')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
//            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error y no se pudo crear el perfil asociado, intentelo de nuevamente mas tarde.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }


    /**
     *Actualizar perfil asociado a cuenta de usuario logueado, solo sino tiene cuenta de usuario
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateAsociado(Request $request, Persona $persona)
    {
        $user = $request->user();

        $rules = [
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nac' => 'required',
            'gen' => 'required',
            'num_doc' => 'required|unique:personas,num_doc,' . $persona->id,
            'email' => 'nullable|email',
            'direccion' => 'required',
            'discapacitado' => 'required',
        ];

        $messages = [
            'nombres.required' => 'El nombre es  requerido',
            'apellidos.required' => 'El apellido es  requerido',
            'fecha_nac.required' => 'La fecha de nacimiento es  requerida',
            'gen.required' => 'El género es  requerido',
            'num_doc.required' => 'El número de identificación es requerido',
            'num_doc.unique' => 'Ya se encuentra registrado un usuario con el número de identificación',
            'email.email' => 'El formato del email es incorrecto',
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

        $existe = Asociado::where('user_id', $user->id)->where('persona_id', $persona->id)->first();

        if (!$existe) {
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

        } catch (\Exception $e) {
            DB::rollback();
//            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error y no se pudo actualizar el perfil asociado, intentelo de nuevamente mas tarde.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();

        }

    }

    /**
     * No, eliminar, Cambiar estado, accedido solo por trabajadores
     *
     * @param  \App\Persona $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Persona $persona)
    {
        if ($request->user()->can('delete_personas')) {

            $persona->estado == Persona::PERFIL_INACTIVO ? $persona->estado = Persona::PERFIL_ACTIVO : $persona->estado = Persona::PERFIL_INACTIVO;
            $persona->update();
            return response()->json(['data' => $persona], 200);

        }else abort(403);
    }
}
