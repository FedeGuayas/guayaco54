@extends('layouts.back.plane')

@section('body')

<!-- Main Content -->
<div class="error-page login-wrap bg-cover height-100-p customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
    <img src="{{asset('themes/back/vendors/images/error-bg.jpg')}}" alt="" class="bg_img">
    <div class="pd-10">
        <div class="error-page-wrap text-center color-white">
            <img src="{{asset('images/maintenance.png')}}" alt="">
            <p>Lo sentimos estamos realizando mantenimientos. Volveremos pronto!
            </p>

        </div>
    </div>
</div>

@endsection
