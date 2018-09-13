<?php

namespace App\Http\Controllers;

use App\Asociado;
use App\Escenario;
use App\Permission;
use App\Persona;
use App\Role;
use App\User;
use Carbon\Carbon;
use Facades\App\Classes\UserVerification;
use Illuminate\Http\Request;
use App\Traits\VerifiesUsers;
use Facades\App\Classes\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;


class UserController extends Controller
{

    use VerifiesUsers;

    public function __construct()
    {

//        Carbon::setLocale('es'); //fechas en español local
//        setlocale(LC_TIME, 'es_ES.utf8'); //en el vps
        $this->middleware('auth');
        $this->middleware(['role:admin'], ['except' => ['index', 'show', 'getProfile', 'searchProfile', 'postPassword', 'imageUpload', 'uploadAvatarCrop', 'searchProfileAsociado', 'asociadoStore', 'asociadoCreate', 'asociadoEdit']]);
//        $this->middleware(['role:Admin|Moderador','permission:view_users|add_users|edit_users|delete_users'],['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        setlocale(LC_TIME, 'es');
//        $usuarios = User::latest()->paginate();
        return view('user.index');

    }

    /**
     * Usuarios que no son trabajadores (role!=employee),  ajax
     */
    public function getAllUsers(Request $request)
    {

        if ($request->ajax()) {

            $usuarios = User::
            with('escenario', 'persona', 'facturas', 'asociados','roles','escenario')
                ->leftJoin('escenarios','escenarios.id','=','users.escenario_id')
                ->where('first_name','!=','admin') //no mostrar el admin
//                ->whereHas('roles', function($q){ //con rol=employee
//                    $q->where('name', '=', 'employee');
//                })
//                ->whereDoesntHave('roles', function($query) { //que el rol no sea employee
//                    $query->where('name', '=', 'employee');
//                })
                ->select('users.*');

            $action_buttons = '
            <div class="dropdown">
                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                @can(\'edit_users\')
                    <a class="dropdown-item" href="{{ route(\'users.edit\',[$id]) }}">
                        <i class="fa fa-pencil text-success"></i> Editar
                    </a>
                @endcan
                @can(\'delete_users\')
                    <a class="dropdown-item delete" href="#" data-id="{{$id}}">
                        <i class="fa fa-trash-o text-danger"></i> Eliminar
                    </a>
                @endcan
                </div>
            </div>
                ';

            $datatable = Datatables::of($usuarios)
                ->addColumn('actions', $action_buttons)
                ->addColumn('nombres', function ($usuario) {
                    return $usuario->getFullName();
                })

                ->addColumn('role', function ($usuario) {
                    return $usuario->getRoleNames();
                })
                ->filterColumn('nombres', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(users.first_name,' ',users.last_name) like ?", ["%{$keyword}%"]);
                })
                ->rawColumns(['actions'])
                ->setRowId('id');
            //Agregar variables a a la respuesta json del datatables
            if ($request->draw == 1) {
                $esc = \App\Escenario::distinct('escenario')->pluck('escenario');
                $datatable->with([
                    'allEsc' => $esc
                ]);
            }

            return $datatable->make(true);

        }

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
//        $permisos = Permission::all();
//        $roles = Role::pluck('name', 'id');

        $permisos = Permission::all('name', 'id');
//        $roles = Role::pluck('name', 'id'); //lo devueleve en oren alfabetico no de id
        return view('user.create', compact('roles', 'permisos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles' => 'required'
        ]);

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
//        $password = str_random(6);
        $password = $request->input('password');

        try {

            DB::beginTransaction();

            $user = new User();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            $this->syncPermissions($request, $user);

            $this->addLog('Usuario creado');

            DB::Commit();

            //notificar mediante mailable
//            Mail::to($user)->send(new UserCreated($user));
            //notificar al usuario con un evento
            //  event(new UserCreated($user));

            $notification = [
                'message_toastr' => 'Usuario creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('users.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creción del usuario.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::with('roles')->find($id);
        $roles = Role::all();
        $permisos = Permission::all();
        $escenarios=Escenario::where('status',Escenario::ACTIVO)->get();
        $esc_list=$escenarios->pluck('escenario','id');

        //        $roles = Role::pluck('name', 'id');

        return view('user.edit', compact('user', 'roles', 'permisos','esc_list'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required'
        ]);

        try {

            DB::beginTransaction();

            $user = User::findOrFail($id);

            $first_name = $request->input('first_name');
            $last_name = $request->input('last_name');
            $email = $request->input('email');
            $password = $request->input('password');
            $esc_id = $request->input('escenario_id');

            $user->first_name = $first_name;
            $user->last_name = $last_name;

            if ($request->has('email') && $user->email != $email) {
                //como se actualizo el email del usuario hay k marcarlo como no verificado
                // y generar su token nuevamente para k valide el nuevo email
                //generar y salvar el token de verificacion de email
                UserVerification::generate($user);
                //finalmente asignar el nuevo email
                $user->email = $email;
            }

            if ($password) {
                $user->password = $password;
            }

            if (isset($esc_id)){
                $escenario=Escenario::findOrFail($esc_id);
                $user->escenario()->associate($escenario);
            }

//        $user->fill($request->except('roles', 'permissions', 'password'));
            $this->syncPermissions($request, $user);

            $user->update();

            //si se cambia el correo el usuario esta sin verificar
            if ($user->isPendingVerification()) {
                //enviar email de notificacion al usuario
                UserVerification::sendEmailUpdateVerification($user);
            }

            DB::Commit();

            $this->addLog('Usuario actualizado');

            $notification = [
                'message_toastr' => 'Usuario actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('users.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
//            $message = 'Lo sentimos! Ocurrio un error durante la actualización del usuario.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }
    }

    /**
     * Cambio de contraseña de usuario
     *
     * @param User $user
     * @return mixed
     */
    public function postPassword(Request $request, User $user)
    {
        $rules = [
            'password' => 'required',
            'password_new' => 'confirmed',
            'email_new' => 'required|email|unique:users,email,' . $user->id
//            'account_number' => 'required_if:bank_info,==,on',
        ];

        $messages = [
            'password.required' => 'La contraseña actual es requerida',

            'password_new.confirmed' => 'Las contraseñas no coinciden',

            'email_new.required' => 'Debe escribir el email de usuario',
            'email_new.email' => 'El formato del email no es correcto',
            'email_new.unique' => 'Ya se encuentra en uso el email, debe escoger uno diferente para su cuenta'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return back()->with($notification)->withInput();
        }

        $new_pass = $request->password_new;
        $email = $request->input('email_new');

        $old_email = $user->email;
        $new_email = null;

        try {

            DB::beginTransaction();

            //verificar contraseña actual debe estar correcta
            if (Hash::check($request->password, $user->password)) {
                //si se cambio el email
                if ($request->has('email_new') && $user->email != $email) {
                    //como se actualizo el email del usuario hay k marcarlo como no verificado
                    // y generar su token nuevamente para k valide el nuevo email
                    //generar y salvar el token de verificacion de email
                    UserVerification::generate($user);
                    //finalmente asignar el nuevo email
                    $user->email = $email;
                    $new_email = $email;
                }

                //si se cambia la contraseña
                if ($request->has('password_new')) {
                    $user->password = $new_pass;
                    $user->remember_token = User::onlyGenerateToken();
                }

                $user->update();

            } else {
                $notification = [
                    'message_toastr' => 'La contraseña actual es incorrecta',
                    'alert-type' => 'error'];
                return redirect()->back()->with($notification)->withInput();
            }


            //si se cambia el correo el usuario esta sin verificar y hay que enviarle el correo de verificacion de cuenta
            if ($user->isPendingVerification()) {
                //enviar email de notificacion al usuario
                UserVerification::sendEmailUpdateVerification($user);
            }

            DB::Commit();

            LogActivity::addToLog('Cuenta de usuario actualizada', $user, $new_email != null ? $old_email : null, $new_email != null ? $new_email : null);

            $notification = [
                'message_toastr' => 'Cuenta de usuario actualizada satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('getProfile')->with($notification);

        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
//            $message = 'Lo sentimos! Ocurrio un error durante la actualización de su cuenta.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Subir imagen de usuario
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function imageUpload(Request $request)
    {

        $user = $request->user();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = 'user_avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/dist/img/users/perfil/';//ruta donde se guardara
            $file->move($path, $name);//lo copio a $path con el nombre $name
            $user->avatar = $name;//ahora se guarda  en el atributo avatar el nombre de la imagen
        }

        $user->update();

        $notification = [
            'message_toastr' => 'Imagen de usuario subida correctamente',
            'alert-type' => 'success'];
        return redirect()->route('getProfile')->with($notification);

    }

    /**
     * Actualizar avatar de usuario con imagen cortada con cropper
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAvatarCrop(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $name = 'user_avatar_' . $user->id . '.jpg';
            $path = public_path() . '/dist/img/users/perfil/';
            $file->move($path, $name);
            $user->avatar = $name;
        }

        $user->update();

        return response()->json(['success' => 'done'], 200);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Auth::user()->id == $id) {
            $notification = [
                'message_toastr' => 'No se permite eliminar al usuario actualmente logueado',
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

        try {
            DB::beginTransaction();

            $user = User::where('id', $id)->with('roles')->first();
            $user->delete();
            $this->addLog('Usuario eliminado');
            DB::Commit();
            $notification = [
                'message_toastr' => 'Usuario eliminado correctamente',
                'alert-type' => 'success'];
            return redirect()->route('users.index')->with($notification);
        } catch (\Exception $e) {
            DB::rollback();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error al eliminar el usuario.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Sincronizar los permisos y los roles del usuario
     * @param Request $request
     * @param $user
     * @return mixed
     */
    private function syncPermissions(Request $request, $user)
    {
        $roles = $request->input('roles', []);
        $permissions = $request->input('permissions', []);

        // Obtengo los roles de la bbd a partir del arreglo del request
        $roles = Role::find($roles);

        // Chequeo si el usuario tiene esos mismos roles
        if (!$user->hasAllRoles($roles)) {
            // Resetear los permisos directos del usuario si los roles cambiaron
            $user->permissions()->sync([]);
        } else {
            // Sino cambiaron los roles asigno los permisos del request
//            $user->syncPermissions($permissions);
            $user->permissions()->sync($permissions);
        }

        $user->syncRoles($roles);
        return $user;
    }

    /** Cargar perfil de usuario o crear uno nuevo
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile(Request $request)
    {
//        Carbon::setLocale('es');
        setlocale(LC_TIME, 'es'); //local
//        dd(ucwords(Carbon::now()->formatLocalized('%A %d %B %Y')));

        $user = $request->user();

        $persona = $user->persona;

        $asociados = $user->asociados;

        if (count($user->persona)) {//tiene perfil
            return view('personas.profile', compact('persona', 'asociados'));
        } else {
            return view('personas.create-perfil');
        }
    }

    /**
     * Busca el perfil de la persona si existe, no es privado y no esta asignado a una cuenta de usuario, para asignarlo
     * al usuario logueado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProfile(Request $request)
    {

        if ($request->ajax()) {

            $num_doc = $request->input('identificacion');
            $persona = Persona::with('user')
                ->where('privado', 0)//si no es privado
                ->where('estado', Persona::PERFIL_ACTIVO)
                ->where('num_doc', 'like', '%' . $num_doc . '%')
                ->first();

            //Si se encuentra a la persona y esta no tiene perfil vinculado a cuenta de usuario la muestro
            if (count($persona) > 0 && !isset($persona->user)) {
                return response()->json(['persona' => $persona, 'result' => 'found']);
            }
            //de lo contrario no muestro a la persona
            return response()->json(['message' => 'No se encontraron registros, o la persona tiene una cuenta de usuario asociada o su perfil es privado', 'result' => 'not-found']);
        }

    }

    /**
     * Busca el perfil de la persona si existe y no es privado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProfileAsociado(Request $request)
    {

        if ($request->ajax()) {

            $num_doc = $request->input('identificacion');

            $persona = Persona::with('user')
                ->where('estado', Persona::PERFIL_ACTIVO)
                ->where('num_doc', 'like', '%' . $num_doc . '%')
                ->first();

            //Se encontro a la persona, tiene cuenta asociada y es privado
            if (count($persona) > 0 && isset($persona->user) && $persona->privado == Persona::PERFIL_PRIVADO) {
                return response()->json(['message' => 'Perfil privado y no se puede mostrar', 'result' => 'not-found']);
            }

            //Si se encuentra a la persona y esta no tiene perfil vinculado a cuenta de usuario la muestro.
            // este asociado puede ser editado
            if (count($persona) > 0 && !isset($persona->user)) {
                return response()->json(['persona' => $persona, 'result' => 'found']);
            }

            //Se encontro a la persona, tiene cuenta asociada y no es privado
            //este perfil se puede asociar pero no editar sus datos
            if (count($persona) > 0 && isset($persona->user) && $persona->privado == Persona::PERFIL_PUBLICO) {
                return response()->json(['persona' => $persona, 'result' => 'found']);
            }

            //de lo contrario no muestro a la persona
            return response()->json(['message' => 'Registros no encontrados. Puede agregar un perfil de un amigo y asociarlo a su cuenta', 'result' => 'not-found']);
        }

    }


    /**
     * Guardar el perfil asociado a la cuenta de usuario
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function asociadoStore(Request $request)
    {

        $user = $request->user();
        $persona_id = $request->input('persona_id_show');

        try {

            DB::beginTransaction();

            $persona = Persona::where('id', $persona_id)->first();

            if (count($persona_id) > 0) {
                $asociado = new Asociado();
                $asociado->user_id = $user->id;
                $asociado->persona_id = $persona->id;
                $asociado->save();
            } else {
                $notification = [
                    'message_toastr' => 'No se encontró el perfil',
                    'alert-type' => 'error'];
                return redirect()->route('getProfile')->with($notification);
            }

            DB::Commit();

            $notification = [
                'message_toastr' => 'Se vinculó correctamente el prefil a su cuenta de usuario',
                'alert-type' => 'success'];
            return redirect()->route('getProfile')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
//            $message=$e->getMessage();
            $message = 'Lo sentimos ocurrio un error al intentar asociar el perfil';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification);

        }
    }

    /**
     * Cargar vista para crear perfil asociado nuevo
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function asociadoCreate(Request $request)
    {
        return view('personas.create-perfil-asociado');
    }

    /**
     * Cargar vista para editar perfil asociado,
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function asociadoEdit(Request $request, Persona $persona)
    {
        $user = $request->user();

        //comprobar que el perfil que se va a editar este asociado al usuario logueado
        $existe = Asociado::where('user_id', $user->id)->where('persona_id', $persona->id)->first();

        if (!$existe) {
            abort(403);
        }

        // Si el perfil que se desea editar tiene una cuenta de usuario asociada, o es privada
        if ((isset($persona->user) || $persona->privado == Persona::PERFIL_PRIVADO)) {
            $message = 'No puede editar un perfil privado o que no tenga asociado a su cuenta';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->route('getProfile')->with($notification);
        }
        return view('personas.edit-perfil-asociado', compact('persona'));
    }

    /**
     * Manejar el tipo de log de inicio de session
     * @param $tipo
     */
    public function userLoginLog($tipo)
    {
        switch ($tipo) {
            case 'login_success':
                $this->addLog('Inicio de session correcto');
                break;
            case 'login_fail':
                $this->addLog('Error de inicio de session');
                break;
        }

    }

    /**
     * Guaradar el log en la bbdd
     * @param $message
     */
    protected function addLog($message)
    {
        LogActivity::addToLog($message);
    }


}
