<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//
//    return view('welcome');
//});

Route::get('/', 'HomeController@getwelcome')->name('getWelcome');

Auth::routes();

/*
 * User verification
 */
Route::get('email-verification/error/{message}', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');


/*
 *Usuarios autenticados y con cuentas verificadas
 */
Route::middleware(['auth', 'isVerified'])->group(function () {

    /*
     * Roles que pueden acceder solo al home del back-end
     */
    Route::middleware(['role:admin|employee|client|registered'])->group(function () {
        //mostrar el home
        Route::get('/home', 'HomeController@index')->name('home');

        //Ver terminos
        Route::get('/terms', 'HomeController@getTerms')->name('terms');
    });

    /*
     * Roles con accesos al back-end como usuarios online
     */
    Route::middleware(['role:admin|employee|client|registered'])->prefix('guest')->group(function () {

        //mostrar perfil del usuario
        Route::get('profile', 'UserController@getProfile')->name('getProfile');
        //buscar perfil por la identificacion. Usuario con cuenta nueva
        Route::post('search/profile', 'UserController@searchProfile')->name('perfil.search');
        //buscar perfil sin cuenta de usuario para asociarlo a la cuenta logueada y poder inscribirlos posteriormente
        Route::post('search/profile-asociado', 'UserController@searchProfileAsociado')->name('perfil-asociado.search');
        //guardar asociado (de perfil existente)
        Route::post('profile/asociado/store', 'UserController@asociadoStore')->name('perfil-asociado.store');
        //eliminar asociado
        Route::delete('profile/asociado/destroy{asociado}', 'AsociadoController@destroy')->name('perfil-asociado.destroy');
        //cargar vista para crear perfil asociado nuevo
        Route::get('profile/asociado-profile', 'UserController@asociadoCreate')->name('perfil-asociado.create');
        //guardar perfil asociado nuevo
        Route::post('profile/asociado-profile/store', 'PersonaController@storeAsociado')->name('perfil-asociado.store.new');
        //cargar vista para editar perfil asociado
        Route::get('profile/asociado-profile/edit/{persona}', 'UserController@asociadoEdit')->name('perfil-asociado.edit');
        //actualizar perfil de amigo asociado a cuenta
        Route::put('profile/asociado-profile/{persona}/update', 'PersonaController@updateAsociado')->name('perfil-asociado.update');


        //cambiar contraseña de usuario
        Route::put('users/{user}/password-update', 'UserController@postPassword')->name('user.password.update');
        //subir avatar de usuario con dropzone
        Route::post('user/image-save', 'UserController@imageUpload')->name('user.avatar.upload');
        //actualizar avatar de usuario cortado con cropperjs
        Route::post('user/avatar-update', 'UserController@uploadAvatarCrop')->name('user.avatarCrop.upload');

        //vista para inscripcion de usuario online
        Route::get('inscription', 'InscripcionController@create')->name('user.inscripcion.create');
        //Ajax para inscripciones
        Route::get('inscription/category/circuit', 'InscripcionController@getCategoriaCircuito')->name('getCategoriaCircuito');
        //Actualizar costo
        Route::get('inscription/cost/{data?}', 'InscripcionController@userOnlineCosto')->name('user.getCosto');
        //Obtener stock de talla
        Route::get('inscription/talla/stock/{data?}', 'InscripcionController@getTallaStock')->name('user.getTallaStock');

        //Personas/index obtener todas las personas  AJAX
        Route::get('persons/getAll', 'PersonaController@getAllPersonas')->name('getAllPersonas');
        //perfiles de las personas que se inscribiran
        Route::resource('personas', 'PersonaController');

    });

    /*
     * Roles con acceso a todas las rutas de administracion en el back-end SOLO EMPLEADOS
     */
    Route::middleware(['role:admin|employee'])->prefix('admin')->group(function () {

        Route::post('circuitos/{id}/status', 'CircuitoController@setStatus')->name('circuitos.set.status');
        Route::post('categorias/{id}/status', 'CategoriaController@setStatus')->name('categorias.set.status');
        Route::post('escenarios/{id}/status', 'EscenarioController@setStatus')->name('escenarios.set.status');
        Route::post('deportes/{id}/status', 'DeporteController@setStatus')->name('deportes.set.status');

        //muestra el stock de las tallas para las incripciones
        Route::get('tallas/getstock', 'TallaController@getTallaStock')->name('tallas.getStock');

        //Users/index obtener todos los usuarios  AJAX
        Route::get('users/getAll', 'UserController@getAllUsers')->name('getAllUsers');

        //Vista configuracion
        Route::get('configurations', 'ConfiguracionController@index')->name('admin.configurations.index');
        //Guardar configuracion
        Route::post('configurations/create', 'ConfiguracionController@create')->name('admin.configurations.store');

        //Guardar cliente
        Route::post('cliente/create', 'PersonaController@storeBack')->name('admin.cliente.store');
        //Update cliente
        Route::put('cliente/{persona}/update', 'PersonaController@updateBack')->name('admin.cliente.update');

        //vista para crear inscripcion de un cliente en backend
        Route::get('inscription/{persona}', 'InscripcionController@createBack')->name('admin.inscripcion.create');
        //vista para editar inscripcion de un cliente en backend
//        Route::get('inscription/{id}/edit', 'InscripcionController@editBack')->name('admin.inscripcion.edit');
        //Ajax para inscripciones obterner los circuitos para la categoria seleccionada
        Route::get('inscription/category/getCircuit', 'InscripcionController@getCatCir')->name('getCatCir');
        //Actualizar costo
        Route::get('inscriptions/cost/{data?}', 'InscripcionController@getCosto')->name('admin.getCosto');
        //Guardar Inscripcion de Backend
        Route::post('inscription/store', 'InscripcionController@store')->name('admin.inscription.store');
        //Listar todas las inscripciones
        Route::get('inscription', 'InscripcionController@index')->name('admin.inscription.index');
        Route::get('inscriptions/getAll', 'InscripcionController@getAll')->name('admin.inscription.getAll');
        //Vista para editar inscripcion
        Route::get('inscription/{inscripcion}/edit', 'InscripcionController@edit')->name('admin.inscription.edit');
        //Actualizar inscripcion
        Route::put('inscription/{inscripcion}', 'InscripcionController@update')->name('admin.inscription.update');
        //Eliminar inscripcion, pone a status c=cancelada
        Route::delete('inscription/{inscripcion}', 'InscripcionController@destroy')->name('admin.inscription.destroy');
        //Entregar Kit
        Route::post('inscription/{inscripcion}/setKit', 'InscripcionController@setKit')->name('admin.inscripcion.setKit');



        Route::resource('users', 'UserController');
        Route::resource('categorias', 'CategoriaController', ['except' => ['show', 'destroy']]);
        Route::resource('circuitos', 'CircuitoController', ['except' => ['show', 'destroy']]);
        Route::resource('descuentos', 'DescuentoController', ['except' => ['show']]);
        Route::resource('tallas', 'TallaController', ['except' => ['show']]);
        Route::resource('escenarios', 'EscenarioController', ['except' => ['show', 'destroy']]);
        Route::resource('deportes', 'DeporteController', ['except' => ['show', 'destroy']]);
        Route::resource('ejercicios', 'EjercicioController', ['except' => ['show', 'destroy']]);
        Route::resource('taxes', 'ImpuestoController', ['except' => ['show', 'destroy']]);
        Route::resource('categoria-circuito', 'CategoriaCircuitoController', ['except' => ['show', 'destroy']]);
        Route::resource('productos', 'ProductoController');

        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::resource('log-activity', 'LogActivityController');

    });


});

