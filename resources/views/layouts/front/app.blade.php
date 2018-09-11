<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Site made with Mobirise Website Builder v4.8.1, https://mobirise.com -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="Guayaco Runner, guayaco, fedeguayas">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <meta name="description" content="Guayaco Runner es una carrera de integración  organizada por la Federación Deportiva del Guayas">

    <link rel="shortcut icon" href="{{asset('themes/front/assets/images/icon.png')}}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>


    <title>{{ config('app.name', 'Guayaco') }}</title>

    <!-- Fontawesome-->
    <link href="{{ asset('plugins/fontawesome/css/fontawesome-all.min.css') }}" rel="stylesheet">

    <!-- Toastr-->
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <link rel="stylesheet"
          href="{{asset('themes/front/assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/web/assets/mobirise-icons/mobirise-icons.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/tether/tether.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/bootstrap/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/bootstrap/css/bootstrap-reboot.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/socicon/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/animatecss/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/dropdown/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/theme/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('themes/front/assets/mobirise/css/mbr-additional.css')}}" type="text/css">

    @stack('styles')
</head>
<body>
<section class="menu cid-qTkzRZLJNu" once="menu" id="menu1-0">

    {{--navbar--}}
    @include('layouts.front.partials.navbar')

</section>

@yield('content')

<section class="cid-qTkAaeaxX5 mbr-reveal" id="footer1-2">

@include('layouts.front.partials.footer')

</section>


<script src="{{asset('themes/front/assets/web/assets/jquery/jquery.min.js')}}"></script>
<script src="{{asset('themes/front/assets/popper/popper.min.js')}}"></script>
<script src="{{asset('themes/front/assets/tether/tether.min.js')}}"></script>
<script src="{{asset('themes/front/assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('themes/front/assets/ytplayer/jquery.mb.ytplayer.min.js')}}"></script>
<script src="{{asset('themes/front/assets/vimeoplayer/jquery.mb.vimeo_player.js')}}"></script>
<script src="{{asset('themes/front/assets/bootstrapcarouselswipe/bootstrap-carousel-swipe.js')}}"></script>
<script src="{{asset('themes/front/assets/viewportchecker/jquery.viewportchecker.js')}}"></script>
<script src="{{asset('themes/front/assets/dropdown/js/script.min.js')}}"></script>
<script src="{{asset('themes/front/assets/touchswipe/jquery.touch-swipe.min.js')}}"></script>
<script src="{{asset('themes/front/assets/smoothscroll/smooth-scroll.js')}}"></script>
<script src="{{asset('themes/front/assets/theme/js/script.js')}}"></script>
<script src="{{asset('themes/front/assets/slidervideo/script.js')}}"></script>
<script src="{{asset('themes/front/assets/formoid/formoid.min.js')}}"></script>

<!-- Toastr-->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

@stack('scripts')
<div id="scrollToTop" class="scrollToTop mbr-arrow-up"><a style="text-align: center;"><i></i></a></div>
<input name="animation" type="hidden">
</body>
</html>