<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Guayaco') }}</title>

    <!-- Fontawesome-->
    <link href="{{ asset('plugins/fontawesome/css/fontawesome-all.min.css') }}" rel="stylesheet">

    <!--App styles-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--Datatables-->
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/DataTables/datatables.min.css')}}"/>

    <!-- Toastr-->
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">


    @stack('styles')

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Administracion <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    @role('admin')
                                    <a href="{{ route('log-activity.index') }}">Logs</a>
                                    <a href="{{ route('permissions.index') }}">Permisos</a>
                                    <a href="{{ route('roles.index') }}">Roles</a>
                                    @endrole
                                    <a href="{{ route('users.index') }}">Usuarios</a>
                                </li>
                            </ul>
                        </li>



                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<!--Jquery 3.3.1 -->
<script type="tet/javascript" src="{{asset('plugins/jQuery/jquery.js')}}"></script>
<!--Datatables -->
<script type="text/javascript" src="{{asset('plugins/DataTables/datatables.min.js')}}"></script>
<!-- Toastr-->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

@stack('scripts')


</body>
</html>
