@extends('layouts.back.plane')

@section('body')
    <div class="error-page login-wrap bg-cover height-100-p customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
        <img src="{{asset('themes/back/vendors/images/error-bg.jpg')}}" alt="" class="bg_img">
        <div class="pd-10">
            <div class="error-page-wrap text-center color-white">
                <h1 class="color-white weight-500">Error: 500Error Inesperado</h1>
                <img src="{{asset('themes/back/vendors/images/500.png')}}" alt="">
                <p>Se produjo un error y su solicitud no pudo completarse..<br>Verifique la URL, <a href="{{route('home')}}">Ir al Inicio</a>.</p>
            </div>
        </div>
    </div>
@endsection