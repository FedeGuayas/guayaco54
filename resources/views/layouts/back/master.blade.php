@extends('layouts.back.plane')

@section('body')

    @include('layouts.back.include.header')
    @include('layouts.back.include.sidebar')

    <div class="main-container">
        <div class="pd-ltr-20 customscroll customscroll-10-p height-100-p xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <h4>@yield('page_title')</h4>
                            </div>
                            @yield('breadcrumbs')
                            {{--<nav aria-label="breadcrumb" role="navigation">--}}
                                {{--<ol class="breadcrumb">--}}
                                    {{--<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>--}}
                                    {{--<li class="breadcrumb-item active" aria-current="page">Perfil</li>--}}
                                {{--</ol>--}}
                            {{--</nav>--}}
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
            @include('layouts.back.include.footer')
        </div>

    </div>

@endsection
