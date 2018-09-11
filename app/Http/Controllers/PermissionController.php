<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();

        return view('permissions.index', compact('permissions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();

        return view('permissions.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|max:40|unique:permissions,name'
        ];

        $messages = [
            'name.required' => 'EL nombre del permiso es un campo requerido',
            'name.unique' => 'EL nombre del permiso ya se encuentra en uso',
            'name.max' => 'EL nombre del permiso es demasiado largo, no debe sobrepasar los 40 caracteres',
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

            $roles = $request['roles'];

            $permission = new Permission();
            $permission->name = $request->input('name');
            $permission->save();

            if (!empty($request['roles'])) { //si se selecciona algun role
                foreach ($roles as $role) {
                    $r = Role::where('id', '=', $role)->firstOrFail(); //buscar role en bbdd que coincida con el id del seleccionado
//                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                    $r->givePermissionTo($permission);
                }
            }

            //agregar al admin todos los permisos
            $rol_admin = Role::where('name', '=', 'admin')->firstOrFail();
            $rol_admin->syncPermissions(Permission::all());

            DB::Commit();

            $notification = [
                'message_toastr' => 'Permiso creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('permissions.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
//            $message = $e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del permiso.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $rules = [
            'name' => 'required|max:40|unique:permissions,name,' . $permission->id
        ];

        $messages = [
            'name.required' => 'EL nombre del permiso es un campo requerido',
            'name.unique' => 'EL nombre del permiso ya se encuentra en uso',
            'name.max' => 'EL nombre del permiso es demasiado largo, no debe sobrepasar los 40 caracteres',
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

            $input = $request->all();
            $permission->fill($input)->save();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Permiso actualizado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('permissions.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            //            $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización del permiso.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

        //si se cargararn los relos para el edit
        ///
//        if($role = Role::findOrFail($id)) {
//            // admin role has everything
//            if($role->name === 'Admin') {
//                $role->syncPermissions(Permission::all());
//                return redirect()->route('roles.index');
//            }
//
//            $permissions = $request->get('permissions', []);
//            $role->syncPermissions($permissions);
//            flash( $role->name . ' permissions has been updated.');
//        } else {
//            flash()->error( 'Role with id '. $id .' note found.');
//        }
//
//        return redirect()->route('roles.index');
//

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $perm_adm = Permission::where('name', 'LIKE', '%_roles')
            ->orWhere('name', 'LIKE', '%_permissions')
            ->select('name')
            ->get()->toArray();

        $perm_adm = array_flatten($perm_adm);

//        //Si es un permiso administrativo no permitir eliminarlo
        if (in_array($permission->name, $perm_adm)) {
            return response()->json(['data' => 'Acceso denegado'], 403);
        }


        $permission->delete();
        return response()->json(['data' => $permission], 200);

    }


}
