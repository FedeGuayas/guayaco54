<div class="left-side-bar">
    <div class="brand-logo">
        <a href="{{url('/')}}">
            <img src="{{asset('themes/back/src/images/logo-header-1-119x27.png')}}" alt="">
        </a>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-home"></span><span class="mtext">Inicio</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('home')}}">Reglamento</a></li>
                    </ul>
                </li>

                @role('admin')
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="fa fa-gears"></span><span class="mtext">Parametrización</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('circuitos.index')}}">Circuitos</a>
                        <li><a href="{{route('categorias.index')}}">Categorias</a>
                        {{--<li><a href="{{route('categoria-circuito.index')}}">Categoria / Circuitos</a>--}}
                        <li><a href="{{route('productos.index')}}">Productos</a>
                        <li><a href="{{route('tallas.index')}}">Tallas</a>
                        <li><a href="{{route('escenarios.index')}}">Escenarios</a>
                        <li><a href="{{route('deportes.index')}}">Deportes</a>
                        <li><a href="{{route('descuentos.index')}}">Descuentos</a>
                    </ul>
                </li>
                @endrole
                @hasanyrole(['admin','employee'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-archive"></span><span class="mtext">Inscripciones FDG</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('personas.index')}}">Clientes a Inscribir</a></li>
                        <li><a href="{{route('inscriptions.index')}}">Inscripciones</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-money"></span><span class="mtext">Comprobantes FDG</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('facturas.index')}}">Comprobantes</a></li>
                        <li><a href="{{route('admin.facturacion.arqueo')}}">Arqueo</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('admin.inscripcions.reservas')}}" class="dropdown-toggle no-arrow">
                        <span class="fa fa-pencil-square"></span><span class="mtext">Insc. Pendientes <span
                                    class="badge-pill badge-danger ">{{ Session::has('reservas') ?  Session::get('reservas') : ''}}</span>
                        </span>
                    </a>
                </li>
                @endhasanyrole
                @role('admin')
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-gears"></span><span class="mtext">Administración</span>
                    </a>
                    <ul class="submenu">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fas fa-users-cog"></i><span class="mtext"><i class="fa fa-users"
                                                                                       aria-hidden="true"></i> Usuarios </span>
                            </a>
                            <ul class="submenu child">
                                <li><a href="{{route('users.index')}}">Activos <i class="icon-copy fa fa-check-circle-o"
                                                                                  aria-hidden="true"></i></a></li>
                                <li><a href="#">Inactivos <i class="fa fa-user-times" aria-hidden="true"></i></a></li>
                            </ul>
                        </li>
                        <li><a href="{{route('roles.index')}}">Roles</a>
                        <li><a href="{{route('permissions.index')}}">Permisos</a>
                        <li><a href="{{route('admin.configurations.index')}}">Configuración</a>
                        <li><a href="#">Importar Chips</a>
                        <li><a href="#">Importar Resultados</a>
                    </ul>
                </li>
                @endrole
                @role('admin')
                <li>
                    <a href="#" class="dropdown-toggle no-arrow">
                        <span class="fa fa-list-alt"></span><span class="mtext">Logs</span>
                    </a>
                </li>
                @endrole
                {{--<li>--}}
                {{--<a href="chat.php" class="dropdown-toggle no-arrow">--}}
                {{--<span class="fa fa-comments-o"></span><span class="mtext">Chat <span--}}
                {{--class="fi-burst-new text-danger new"></span></span>--}}
                {{--</a>--}}
                {{--</li>--}}

                @role('client')
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-user-o"></span><span class="mtext">Menú Usuario</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('inscription.create')}}">Nueva Inscripción <i
                                        class="fa fa-pencil-square-o"></i></a>
                        <li><a href="{{route('user.getComprobantes')}}">Comprobantes <span class="badge-pill badge-danger ">
                                    {{ Session::has('insc_pagar') ?  Session::get('insc_pagar') : '0'}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasallroles
            </ul>
        </div>
    </div>
</div>