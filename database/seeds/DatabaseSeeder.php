<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //deshabilito las foreign key para evitar problemas en las migraciones
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Preguntar por la migracion refresh de la bbdd, default es no
        if ($this->command->confirm('Realizar refresh migration antes de los seed?, esto borrara todos los datos!')) {
            // Call the php artisan migrate:refresh
            $this->command->call('migrate:refresh');
            $this->command->warn("Datos limpiados, iniciando una bbdd en blanco.");
        }

        // Apunto al arreglo con los permisos agregados en el modelo
        $permissions = Permission::defaultPermissions();

        //Se crea cada permiso recorriendo el arreglo creado en el modelo Permission
        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        $this->command->info('Agregados los permisos por defecto.');

        // Confirmar para crear los roles
        if ($this->command->confirm('Crear roles para cada usuario, predeterminados son Administrador, Empleado, Cliente, Registrado y Anonimo? [y|N]', true)) {

            // Preguntar por los nombres de los roles
            $input_roles = $this->command->ask('Entre los roles separados por coma. Los siguientes se crearan por defecto sino escribe ninguno', 'admin,employee,client,registered,anonymous');

            // Explode roles separados por coma
            $roles_array = explode(',', $input_roles);

            // Agregar roles
            foreach ($roles_array as $role) {
                $role = Role::firstOrCreate(['name' => trim($role)]);

                if ($role->name == 'admin') {
                    // Asiganr todos los permisos al admin
                    $role->syncPermissions(Permission::all());
                    $this->command->info('Todos los permisos concedidos al Admin');
                } else {
                    // para los demas roles por defecto solo accesos de lectura
                    $role->syncPermissions(Permission::where('name', 'LIKE', 'view_%')->get());
                }

                // crear un usuario por cada rol
                $this->createUser($role);
            }

            $this->command->info('Roles: ' . $input_roles . ', agregados correctamente');

        } else {
            Role::firstOrCreate(['name' => 'user']);
            $this->command->info('Agregado solamente el rol de usuario por defecto "User".');
        }

        $cant_users=100;

        //llamada a demas seeders
        //datos de prueba de users
        factory(User::class,$cant_users)->create();
        $this->command->info('Adicionando algunos datos de prueba para Users.');

        //escenarios
        $this->call(EscenariosTableSeeder::class);
        //formas de pago
        $this->call(MPagosTableSeeder::class);
        
        $this->command->warn('Completado :)');

    } //fin run


    /**
     * Crear un usuario con un rol dado
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = factory(User::class)->create();
        $user->assignRole($role->name);

        if ($role->name == 'admin') {

            $user->password='secret';
            $user->verified=true;
            $user->update();

            $this->command->info('Datos de acceso para el administrador:');
            $this->command->warn($user->email);
            $this->command->warn('El password es  "secret"');
        }
    }


}
