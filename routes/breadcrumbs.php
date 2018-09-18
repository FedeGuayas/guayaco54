<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Inicio', route('home'));
});

// Home>Profile
Breadcrumbs::register('perfil', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Perfil', route('getProfile'));
});

// Home>Profile
Breadcrumbs::register('perfil-create', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Crear Perfil', route('getProfile'));
});

//Home>Profile [Asociado] >Create
Breadcrumbs::register('perfil-as-create', function($breadcrumbs)
{
    $breadcrumbs->parent('perfil');
    $breadcrumbs->push('Crear Perfil', route('getProfile'));
});

//Home>Profile [Asociado] > Edit
Breadcrumbs::register('perfil-as-edit', function($breadcrumbs)
{
    $breadcrumbs->parent('perfil');
    $breadcrumbs->push('Editar Perfil', route('getProfile'));
});


// Home>Parametrizacion>Circuitos
Breadcrumbs::register('circuito', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Circuitos', route('circuitos.index'));
});
// Home>Parametrizacion>Circuitos>Crear
Breadcrumbs::register('circuito-create', function($breadcrumbs)
{
    $breadcrumbs->parent('circuito');
    $breadcrumbs->push('Crear circuito', route('circuitos.index'));
});
// Home>Parametrizacion>Circuitos>Edit
Breadcrumbs::register('circuito-edit', function($breadcrumbs,$circuito)
{
    $breadcrumbs->parent('circuito');
    $breadcrumbs->push('Editar circuito '.$circuito->circuito, route('circuitos.index'));
});


// Home>Parametrizacion>Categorias
Breadcrumbs::register('categoria', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Categorias', route('categorias.index'));
});
// Home>Parametrizacion>Categorias>Crear
Breadcrumbs::register('categoria-create', function($breadcrumbs)
{
    $breadcrumbs->parent('categoria');
    $breadcrumbs->push('Crear categoría', route('categorias.index'));
});
// Home>Parametrizacion>Categorias>Edit
Breadcrumbs::register('categoria-edit', function($breadcrumbs,$categoria)
{
    $breadcrumbs->parent('categoria');
    $breadcrumbs->push('Editar categoría '.$categoria->categoria, route('categorias.index'));
});

// Home>Parametrizacion>Tallas
Breadcrumbs::register('talla', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Tallas', route('tallas.index'));
});
// Home>Parametrizacion>Tallas>Crear
Breadcrumbs::register('talla-create', function($breadcrumbs)
{
    $breadcrumbs->parent('talla');
    $breadcrumbs->push('Crear talla', route('tallas.index'));
});
// Home>Parametrizacion>Tallas>Edit
Breadcrumbs::register('talla-edit', function($breadcrumbs,$talla)
{
    $breadcrumbs->parent('talla');
    $breadcrumbs->push('Editar talla '.$talla->talla.', color: '.$talla->color, route('tallas.index'));
});

// Home>Parametrizacion>Escenarios
Breadcrumbs::register('escenario', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Escenarios', route('escenarios.index'));
});
// Home>Parametrizacion>Escenarios>Crear
Breadcrumbs::register('escenario-create', function($breadcrumbs)
{
    $breadcrumbs->parent('escenario');
    $breadcrumbs->push('Crear escenario', route('escenarios.index'));
});
// Home>Parametrizacion>Escenario>Edit
Breadcrumbs::register('escenario-edit', function($breadcrumbs,$escenario)
{
    $breadcrumbs->parent('escenario');
    $breadcrumbs->push('Editar escenario '.$escenario->escenario, route('escenarios.index'));
});


// Home>Parametrizacion>Deportes
Breadcrumbs::register('deporte', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Deportes', route('deportes.index'));
});
// Home>Parametrizacion>Deportes>Crear
Breadcrumbs::register('deporte-create', function($breadcrumbs)
{
    $breadcrumbs->parent('deporte');
    $breadcrumbs->push('Crear deporte', route('deportes.index'));
});
// Home>Parametrizacion>Deporte>Edit
Breadcrumbs::register('deporte-edit', function($breadcrumbs,$deporte)
{
    $breadcrumbs->parent('deporte');
    $breadcrumbs->push('Editar deporte '.$deporte->deporte, route('deportes.index'));
});



// Home>Administracion>Permisos
Breadcrumbs::register('permiso', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Permisos', route('permissions.index'));
});
// Home>Administracion>Permisos>Crear
Breadcrumbs::register('permiso-create', function($breadcrumbs)
{
    $breadcrumbs->parent('permiso');
    $breadcrumbs->push('Crear permiso', route('permissions.index'));
});
// Home>Administracion>Permiso>Edit
Breadcrumbs::register('permiso-edit', function($breadcrumbs,$permiso)
{
    $breadcrumbs->parent('permiso');
    $breadcrumbs->push('Editar permiso '.$permiso->name, route('permissions.index'));
});



// Home>Administracion>Roles
Breadcrumbs::register('role', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Roles', route('roles.index'));
});
// Home>Administracion>Roles>Crear
Breadcrumbs::register('role-create', function($breadcrumbs)
{
    $breadcrumbs->parent('role');
    $breadcrumbs->push('Crear Rol', route('roles.index'));
});
// Home>Administracion>Role>Edit
Breadcrumbs::register('role-edit', function($breadcrumbs,$role)
{
    $breadcrumbs->parent('role');
    $breadcrumbs->push('Editar role '.$role->name, route('roles.index'));
});




// Home>Administracion>Usuarios
Breadcrumbs::register('user', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Usuarios', route('users.index'));
});
// Home>Administracion>Usuarios>Crear
Breadcrumbs::register('user-create', function($breadcrumbs)
{
    $breadcrumbs->parent('user');
    $breadcrumbs->push('Crear Usuario', route('users.index'));
});
// Home>Administracion>Usuario>Edit
Breadcrumbs::register('user-edit', function($breadcrumbs,$user)
{
    $breadcrumbs->parent('user');
    $breadcrumbs->push('Editar usuario '.$user->getFullName(), route('users.index'));
});

// Home>Terminos
Breadcrumbs::register('terminos', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Términos', route('terms'));
});