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
                        <li><a href="{{route('tallas.index')}}">Tallas</a>
                        <li><a href="{{route('escenarios.index')}}">Escenarios</a>
                        <li><a href="{{route('deportes.index')}}">Deportes</a>
                    </ul>
                </li>
                @endrole
                @hasanyrole(['admin','employee'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-archive"></span><span class="mtext">Inscripciones</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Personas a Inscribir</a></li>
                        <li><a href="#">Inscripciones</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <span class="fa fa-money"></span><span class="mtext">Facturación</span>
                            </a>
                            <ul class="submenu child">
                                <li><a href="#">Comprobantes</a></li>
                                <li><a href="#">Cuadre</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endhasanyrole
                @role('admin')
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-gears"></span><span class="mtext">Administración</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{route('users.index')}}">Usuario</a>
                        <li><a href="{{route('roles.index')}}">Roles</a>
                        <li><a href="{{route('permissions.index')}}">Permisos</a>
                        <li><a href="#">Configuración</a>
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

                @hasallroles(['registered','client'])
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <span class="fa fa-user-o"></span><span class="mtext">Menú Usuario</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Nueva Inscripción <i class="fa fa-pencil-square-o"></i></a>
                        <li><a href="#">Comprobantes <i class="fa fa-sticky-note-o"></i></a>
                    </ul>
                </li>
                @endhasallroles
            </ul>
        </div>
    </div>
</div>