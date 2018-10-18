<div class="pre-loader"></div>
<div class="header clearfix">
    <div class="header-right">
        <div class="brand-logo">
            <a href="{{url('/')}}">
                <img src="{{asset('themes/back/src/images/icon.png')}}" alt="" class="mobile-logo">
            </a>
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon"><i class="fa fa-user-o"></i></span>
                    <span class="user-name">{{Auth::user()->first_name}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('getProfile')}}"><i class="fa fa-user-md"
                                                                               aria-hidden="true"></i>Mi Perfil</a>
                    <a class="dropdown-item" href="faq.php"><i class="fa fa-question" aria-hidden="true"></i> Ayuda</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> Salir
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>

        @if ( $insc_pagar > 0)
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="badge badge-danger">
                        {{ $insc_pagar }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right ">
                    <a class="dropdown-item" href="{{route('user.getComprobantes')}}"><i class="fa fa-credit-card" aria-hidden="true"></i> Proceder al
                        Pago</a>
                </div>
            </div>
        </div>
        @endif

        <div class="user-notification">
            <a class="nav-item" href="{{route('terms')}}">Términos</a>
        </div>

        <div class="user-notification">
            <a class="nav-item" href="{{route('getReglamento')}}">Reglamento</a>
        </div>


    </div>
</div>