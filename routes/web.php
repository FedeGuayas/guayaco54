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

Route::get('/', function () {

    return view('welcome');
});

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

        Route::resource('personas', 'PersonaController');

    });

    /*
     * Roles con acceso a todas las rutas de administracion en el back-end
     */
    Route::middleware(['role:admin|employee'])->prefix('admin')->group(function () {

        Route::post('circuitos/{id}/status','CircuitoController@setStatus')->name('circuitos.set.status');
        Route::post('categorias/{id}/status','CategoriaController@setStatus')->name('categorias.set.status');
        Route::post('escenarios/{id}/status','EscenarioController@setStatus')->name('escenarios.set.status');
        Route::post('deportes/{id}/status','DeporteController@setStatus')->name('deportes.set.status');

        //muestra el stock de las tallas para las incripciones
        Route::get('tallas/getstock','TallaController@getTallaStock')->name('tallas.getStock');

        //Users/index obtener todos los usuarios  AJAX
        Route::get('users/getAll','UserController@getAllUsers')->name('getAllUsers');




        Route::resource('users', 'UserController');
        Route::resource('categorias', 'CategoriaController',['except'=>['show','destroy']]);
        Route::resource('circuitos', 'CircuitoController',['except'=>['show','destroy']]);
        Route::resource('tallas', 'TallaController',['except'=>['show']]);
        Route::resource('escenarios', 'EscenarioController',['except'=>['show','destroy']]);
        Route::resource('deportes', 'DeporteController',['except'=>['show','destroy']]);

        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::resource('log-activity', 'LogActivityController');

    });


});

