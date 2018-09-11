<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $permissions = $request->input('permissions');

        $rules = [
            'name'=>'required|unique:roles'
        ];

        $messages = [
            'name.required' => 'EL nombre del rol es un campo requerido',
            'name.unique' => 'EL nombre del rol ya se encuentra en uso'
        ];

        $validator = Validator::make($request->all(), $rules,$messages);

        if ($validator->fails()) {
            $notification = [
                'message_toastr' => $validator->errors()->first(),
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

        try {
            DB::beginTransaction();

            $role = new Role();
            $role->name = $name;
            $role->save();

            if (isset($permissions)) {
                //recorrro los permisos seleccionados
                foreach ($permissions as $permission) {
                    $p = Permission::where('id', $permission)->firstOrFail();
                    //asigno el permiso al rol
                    $role->givePermissionTo($p);
                }
            }

            DB::Commit();
            $notification = [
                'message_toastr' => 'Rol creado satisfactoriamente',
                'alert-type' => 'success'];
            return redirect()->route('roles.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
//                        $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la creación del rol.';
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->back()->with($notification)->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
//        $role=Role::findOrFail($id);
//        //Validate name and permission fields
//        $this->validate($request, [
//            'name'=>'required|max:10|unique:roles,name,'.$id,
//            'permissions' =>'required',
//        ]);
//
//        $input = $request->except(['permissions']);
//        $permissions = $request['permissions'];
//        $role->fill($input)->save();
//
//        $p_all = Permission::all();//Get all permissions
//
//        foreach ($p_all as $p) {
//            $role->revokePermissionTo($p); //Remove all permissions associated with role
//        }
//
//        foreach ($permissions as $permission) {
//            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
//            $role->givePermissionTo($p);  //Assign permission to role
//        }
//
//        return redirect()->route('roles.index')
//            ->with('flash_message',
//                'Role'. $role->name.' updated!');

        //V2

        $permissions = $request->input('permissions', []);

        try {

            DB::beginTransaction();

            // el role administardor debe tener todos los permisos
            if ($role->name === 'admin') {
                $role->syncPermissions(Permission::all());
            }else{//sino es admin asignarle los seleccionados
                $role->syncPermissions($permissions);
            }

            $role->update();

            DB::Commit();

            $notification = [
                'message_toastr' => 'Se actualizaron los permisos para el rol '.$role->name,
                'alert-type' => 'success'];
            return redirect()->route('roles.index')->with($notification);

        }catch (\Exception $e){
            DB::rollbBck();
            //                        $message=$e->getMessage();
            $message = 'Lo sentimos! Ocurrio un error durante la actualización de los permisos del rol '.$role->name;
            $notification = [
                'message_toastr' => $message,
                'alert-type' => 'error'];
            return redirect()->route('roles.index')->with($notification)->withInput();

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {

     //Si es un rol permiso administrativo no permitir eliminarlo
        if ($role->name=='admin') {
            return response()->json(['data' => 'Acceso denegado'], 403);
        }

        $role->delete();
        return response()->json(['data' => $role], 200);

    }
}
