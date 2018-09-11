@extends('layouts.back.plane')

@section('body')

    <div class="login-wrap customscroll d-flex align-items-center flex-wrap justify-content-center pd-20">
        <div class="login-box bg-white box-shadow pd-30 border-radius-5">
            <img src="{{asset('themes/back/vendors/images/login-img.png')}}" alt="login" class="login-img">
            <h2 class="text-center mb-30">Restablecer la contraseña</h2>
            <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <p>Ingrese su nueva contraseña, confirme y envíe</p>
                <div class="input-group custom input-group-lg">
                    <input id="email" type="email" name="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           value="{{ $email or old('email')}}" placeholder="Su Email" required autofocus>
                    <div class="input-group-append custom">
                        <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                    </div>
                    @if ($errors->has('email'))
                        <div class="form-text">
                            <strong class="text-danger">{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="input-group custom input-group-lg">
                    <input id="password" type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                           placeholder="Nueva contraseña" required>
                    <div class="input-group-append custom">
                        <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    </div>
                    @if ($errors->has('password'))
                        <div class="form-text">
                            <strong class="text-danger">{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="input-group custom input-group-lg">
                    <input id="password-confirm" type="password"
                           class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                           name="password_confirmation" placeholder="Confirmar nueva contraseña" required>
                    <div class="input-group-append custom">
                        <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="form-text">
                            <strong class="text-danger">{{ $errors->first('password_confirmation') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input class="btn btn-primary btn-lg btn-block" type="submit" value="Enviar">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
