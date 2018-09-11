@extends('layouts.back.plane')

@section('body')

    <div class="login-wrap customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">

        <div class="login-box bg-white box-shadow pd-30 border-radius-5">
            <img src="{{asset('themes/back/vendors/images/login-img.png')}}" alt="login" class="login-img">
            <h2 class="text-center mb-30">Se te olvidó tu contraseña?</h2>
            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <p>Ingrese su correo electrónico para restablecer su contraseña</p>
                <div class="input-group custom input-group-lg">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" required placeholder="Email">
                    <div class="input-group-append custom">
                        <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                    </div>
                    @if ($errors->has('email'))
                        <div class="form-text">
                            <strong class="text-danger">{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Enviar">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="forgot-password">
                            <a href="{{route('register')}}" class="btn btn-outline-primary btn-lg btn-block">Registrarse</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>
            {{--Alertas con Toastr--}}
            @if(Session::has('status'))
    var type = 'success';
    var text_toastr = 'Se envió un enlace a su correo electrónico para restablecer su contraseña';
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}
</script>
@endpush
