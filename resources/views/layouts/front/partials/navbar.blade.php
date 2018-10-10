<nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm bg-color transparent">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </button>
    <div class="menu-logo">
        <div class="navbar-brand">
                <span class="navbar-logo">
                    <a href="#top">
                         <img src="{{asset('themes/front/assets/images/logo-header-1-119x27.png')}}"
                              alt="Fedeguayas_Logo" title="FEDEGUAYAS" style="height: 3.8rem;">
                    </a>
                </span>

        </div>
    </div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav nav-dropdown nav-right" data-app-modern-menu="true">
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}">
                    <span class="mbrib-home mbr-iconfont mbr-iconfont-btn"></span>Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}#features3-15">Historia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}#features17-g">GR-2018</a></li>
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}#reglamento">Reglamento</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}#terminos">Términos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link link text-white display-4" href="{{url('/')}}#form4-6">Contacto</a>
            </li>
            @if (Route::has('login'))
                @if (Auth::check())

                    <li class="nav-item">
                        <a class="nav-link link text-white display-4" href="#">
                            <span class="mbrib-shopping-cart mbr-iconfont mbr-iconfont-btn text-secondary"></span>
                            <span class="badge badge-danger">
                                    {{ $insc_pagar }}
                                </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link link text-white dropdown-toggle display-4" href="#"
                           data-toggle="dropdown-submenu" aria-expanded="false">
                            <span class="mbrib-user mbr-iconfont mbr-iconfont-btn text-secondary"></span>
                            {{Auth::user()->first_name}}
                        </a>
                        <div class="dropdown-menu">
                            <a class="text-white dropdown-item display-4" href="{{route('home')}}"
                               aria-expanded="false">Mi Cuenta
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="text-white dropdown-item display-4" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="mbrib-logout mbr-iconfont mbr-iconfont-btn text-secondary"></span> Salir
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>

                    </li>
                @else
                    <li class="nav-item"><a class="nav-link link text-white display-4"
                                            href="{{ url('/login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link link text-white display-4" href="{{ url('/register') }}">Registro</a>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</nav>