@extends('layouts.back.plane')

@section('body')

<!-- Main Content -->
<div class="error-page login-wrap bg-cover height-100-p customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
    <img src="{{asset('themes/back/vendors/images/error-bg.jpg')}}" alt="" class="bg_img">
    <div class="pd-10">
        <div class="error-page-wrap text-center color-white">
            <img src="{{asset('images/user-no-verified.png')}}" alt="">
            <p>Lo sentimos! <strong>{{$message}} </strong><br>Si olvido su contraseña puede <a href="{{route('password.request')}}">
                    recordarla</a>. Si ya tiene cuenta <a href="{{route('login')}}">inicie sessión</a>. Si no se ha registrado, cree una <a href="{{route('register')}}">cuenta nueva</a>. O vaya al <a href="{{url('/')}}">Inicio</a>.
            </p>

        </div>
    </div>
</div>

@endsection
