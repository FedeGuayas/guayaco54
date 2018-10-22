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


    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
        (function(){ var widget_id = 'jtNSIcirEB';var d=document;var w=window;function l(){var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    </script>
    <!-- {/literal} END JIVOSITE CODE -->

@endsection
