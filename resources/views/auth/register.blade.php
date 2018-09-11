@extends('layouts.front.app')

@section('content')

    <section class="cid-r1cpYtrsiI mbr-fullscreen">

        <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(7, 59, 76);"></div>

        <div class="container">
            <div class="row">
                <div class="col-md-12 offset-lg-3">

                    <div class="card text-white mt-3">
                        <div class="display-2">Registro</div>

                        <div class="card-body">
                            {!! Form::open(['route'=>'register','method'=>'post','id'=>'form-register', 'class'=>'form-horizontal']) !!}
{{--                            <form class="form-horizontal" method="POST" action="{{ route('register') }}" id="form-register">--}}
                                {{--{{ csrf_field() }}--}}

                                <div class="form-group">
                                    <label for="first_name" class="col-md-4 control-label">Nombres</label>
                                    <div class="col-md-6">
                                        <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name"
                                               value="{{ old('first_name') }}" style="text-transform: uppercase;"  autofocus>

                                        @if ($errors->has('first_name'))
                                            <div class="form-text">
                                                <strong class="text-secondary">{{ $errors->first('first_name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="last_name" class="col-md-4 control-label">Apellidos</label>
                                    <div class="col-md-6">
                                        <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name"
                                               value="{{ old('last_name') }}" style="text-transform: uppercase;" required autofocus>

                                        @if ($errors->has('last_name'))
                                            <div class="form-text">
                                                <strong class="text-secondary">{{ $errors->first('last_name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">Su dirección de E-Mail</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                               value="{{ old('email') }}" style="text-transform: lowercase;" required>

                                        @if ($errors->has('email'))
                                            <div class="form-text">
                                            <strong class="text-secondary">{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-md-4 control-label">Contraseña</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                               required>

                                        @if ($errors->has('password'))
                                            <div class="form-text">
                                                <strong class="text-secondary">{{ $errors->first('password') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-4 control-label">Confirme la
                                        Contraseña</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-secondary btn-form display-4" id="guardar">
                                            Registrarme&nbsp; <span class="mbri-edit mbr-iconfont mbr-iconfont-btn" id="icono-registro"></span>
                                             <i class="fas fa-2x fa-spinner fa-pulse hidden" id="icono-spiner"></i>
                                        </button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(document).ready(function () {
        $('form#form-register').on('submit', function (event) {

            $("#guardar").prop("disabled",true);
            $("#icono-registro").prop("hidden",true);
            $("#icono-spiner").removeClass("hidden");

        });
    });

            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    var type = "{{ Session::get('alert-type') }}";
    var text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}
</script>
@endpush