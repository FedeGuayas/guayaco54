<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific Metas -->
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
          content="Guayaco Runner es una carrera de integración  organizada por la Federación Deportiva del Guayas">
    <meta name="keywords"
          content="guayas, deporte, fedeguayas, federacion deportiva del guayas, guayaco runner, carrera, ">
    <meta name="author" content="Ing. Hector Alain Alvarez Gomez">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <title>
        @hasSection ('title')
            {{ config('app.name', 'Guayaco') }} | @yield('title')
        @else
            {{ config('app.name', 'Guayaco') }}
        @endif
    </title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('themes/back/vendors/styles/style.css')}}">

    <!-- Toastr-->
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    @stack('styles')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>--}}
    {{--<script>--}}
    {{--window.dataLayer = window.dataLayer || [];--}}
    {{--function gtag(){dataLayer.push(arguments);}--}}
    {{--gtag('js', new Date());--}}

    {{--gtag('config', 'UA-119386393-1');--}}
    {{--</script>--}}


</head>
<body>

@yield('body')

<!-- js -->

<script src="{{asset('themes/back/vendors/scripts/script.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/air-datepicker/dist/js/i18n/datepicker.es.js')}}"></script>

<!-- Toastr-->
<script src="{{asset('plugins/toastr/toastr.min.js') }}"></script>

<!-- add sweet alert js & css in footer -->
<script src="{{asset('themes/back/src/plugins/sweetalert2/sweetalert2.all.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/sweetalert2/sweetalert2.css')}}">


@stack('scripts')

</body>
</html>