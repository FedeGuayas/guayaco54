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


Route::post('payment/check-out', 'PaymentController@getCallback')->name('getPaymentCallback');

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
        //Ver Reglamento
        Route::get('/reglamento', 'HomeController@getReglamento')->name('getReglamento');

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
//        Route::get('inscription', 'InscripcionController@create')->name('user.inscripcion.create');

        //Personas/index obtener todas las personas  AJAX
        Route::get('persons/getAll', 'PersonaController@getAllPersonas')->name('getAllPersonas');

        //perfiles de las personas que se inscribiran
        Route::resource('personas', 'PersonaController');


        // Obtener lo circuitos para la categoria seleccionada. Ajax
        Route::get('inscription/category/circuit', 'PreInscOnlineController@getCategoriaCircuito')->name('getCategoriaCircuito');
        //Actualizar costo
        Route::get('inscription/cost/{data?}', 'PreInscOnlineController@userCosto')->name('user.getCosto');
        //Obtener stock de tallas
        Route::get('inscription/tallas/stock/{data?}', 'PreInscOnlineController@getTallaStock')->name('user.getTallaStock');
        //Obtener comprobantes del usuario
        Route::get('payment/check-out', 'PreInscOnlineController@getComprobantes')->name('user.getComprobantes');
        //Obtener comprobantes pagados online para realizar reembolsos
        Route::get('payment/refund', 'PaymentController@getRefund')->name('user.getRefund');
        //Realizar reembolso
        Route::get('payment/set-refund', 'PaymentController@setRefund')->name('postRefund');
        //generar auth token payment
        Route::post('payment/get-token', 'PaymentController@paymentezGenerateToken')->name('payment.getToken');
        //Obtener los datos de la preinscripcion al dar en el boton pagar
        Route::post('payment/check-out/getInscripcion', 'PaymentController@getInscripcionPay')->name('user.getInscripcionPay');
        //Actualizar el estado de la inscripcion a pagada y enviar correo al usuario
        Route::post('payment/check-out/setInscripcionPay', 'PaymentController@setFacturaTransID')->name('user.setFacturaTransID');
        //Comprobante de inscripcion, para realizar pago en WU o Contado en Fedeguayas
        Route::get('inscription/comprobante/{inscription}', 'PreInscOnlineController@comprobantePDF')->name('user.comprobantePDF');
        //Registro de inscripcion aprobado y pagado de usuario online
        Route::get('inscription/registro/{inscription}/', 'PreInscOnlineController@registroInscripcion')->name('user.registroInscripcion');


        Route::resource('inscription', 'PreInscOnlineController', ['except' => ['show']]);


        //PAYMENTEZ
        //cancelar
//        Route::get('payment/check-out','PaymentController@getCheckOut')->name('payment.getCheckOut');

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
        Route::get('inscriptions/{persona}', 'InscripcionController@create')->name('inscriptions.create');


        //Ajax para inscripciones obterner los circuitos para la categoria seleccionada
        Route::get('inscription/category/getCircuit', 'InscripcionController@getCatCir')->name('getCatCir');
        //Actualizar costo
        Route::get('inscription/getCost/{data?}', 'InscripcionController@getCosto')->name('admin.getCosto');
        //Listar todas las inscripciones AJAX
        Route::get('inscriptions/get/inscripciones', 'InscripcionController@getAll')->name('admin.getAllInscripcions');
        //Entregar Kit
        Route::post('inscription/{inscripcion}/setKit', 'InscripcionController@setKit')->name('admin.inscripcion.setKit');
        //Print recibo inscripcion backend
        Route::get('inscription/{inscripcion}/print', 'InscripcionController@reciboInscripcion')->name('admin.inscripcion.recibo');

        //exportar inscripciones para programar chips
        Route::get('inscriptions/export/program-chip', 'ChipController@inscripcionesExcelChip')->name('admin.inscription.chip-program');
        //exportar inscripciones echas por el usuario logueado
        Route::get('inscriptions/user/export','InscripcionController@inscripcionesUser')->name('admin.inscription.user.export');

        //Todos los comprobantes ajax
        Route::get('facturas/getAllInside', 'FacturaController@getAll')->name('admin.getAll.inside');
        Route::post('facturas/facturacion-masiva', 'FacturaController@facturacionMasiva')->name('admin.facturacion.masiva');
        Route::get('facturas/arqueo', 'FacturaController@getCuadre')->name('admin.facturacion.arqueo');


        //RESERVAS administradas en el BackEnd
//    Route::group(array('middleware' => 'forceSSL'), function() {
        Route::get('pre-inscripcion/reservas', 'InscripcionController@reservas')->name('admin.inscripcions.reservas');
//    });
        //cancelar reserva
        Route::get('pre-inscripcion/reserva/{id}/cancel','InscripcionController@reservaCancel')->name('admin.reserva.cancel');
        //confirmar reserva
        Route::get('pre-inscripcion/reserva/{id}/confirm','InscripcionController@reservaConfirm')->name('admin.reserva.confirm');
        //editar reserva
        Route::get('pre-inscripcion/reserva/{id}/edit','InscripcionController@reservaEdit')->name('admin.reserva.edit');
        //actualizar forma pago de reserva
        Route::put('pre-inscripcion/reserva/{inscripcion}/update','InscripcionController@reservaUpdate')->name('admin.reserva.update');
        //exportar reservas
        Route::post('pre-inscripcion/reserva/export','InscripcionController@reservasExport')->name('admin.reserva.export');


        Route::resource('users', 'UserController');
        Route::resource('categorias', 'CategoriaController', ['except' => ['show', 'destroy']]);
        Route::resource('circuitos', 'CircuitoController', ['except' => ['show', 'destroy']]);
        Route::resource('descuentos', 'DescuentoController', ['except' => ['show']]);
        Route::resource('tallas', 'TallaController', ['except' => ['show']]);
        Route::resource('escenarios', 'EscenarioController', ['except' => ['show', 'destroy']]);
        Route::resource('inscriptions', 'InscripcionController', ['except' => ['show','create']]);
        Route::resource('deportes', 'DeporteController', ['except' => ['show', 'destroy']]);
        Route::resource('ejercicios', 'EjercicioController', ['except' => ['show', 'destroy']]);
        Route::resource('taxes', 'ImpuestoController', ['except' => ['show', 'destroy']]);
        Route::resource('categoria-circuito', 'CategoriaCircuitoController', ['except' => ['show', 'destroy']]);
        Route::resource('productos', 'ProductoController');
        Route::resource('facturas', 'FacturaController');

        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::resource('log-activity', 'LogActivityController');

    });


});

