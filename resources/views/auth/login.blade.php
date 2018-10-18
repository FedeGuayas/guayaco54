@extends('layouts.front.app')

@section('content')
    {{--@if(Session::has('message_toastr'))--}}
    {{--<div class="alert alert-danger alert-dismissible" role="alert">--}}
    {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
    {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
    {{--{{ Session::get('message_toastr') }}--}}
    {{--@php--}}
    {{--Session::forget('alert');--}}
    {{--@endphp--}}
    {{--</div>--}}
    {{--@endif--}}


    <section class="cid-r1cpYtrsiI mbr-fullscreen">

        <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(7, 59, 76);"></div>

        <div class="container">
            <div class="row">
                <div class="col-md-12 offset-lg-3">

                    <div class="card text-white mt-3">
                        <div class="display-2 ">Inicio de Sesión</div>

                        <div class="card-body">

                            <form class="form-horizontal" method="POST" action="{{ route('login') }}"
                                  novalidate>
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">E-Mail</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               name="email"
                                               value="{{ old('email') }}" required autofocus>

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
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password"
                                               required>

                                        @if ($errors->has('password'))
                                            <div class="form-text">
                                                <strong class="text-secondary">{{ $errors->first('password') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                Recordarme
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">

                                        <button type="submit" class="btn btn-secondary btn-form display-4">
                                            <span class="mbri-login mbr-iconfont mbr-iconfont-btn"></span>&nbsp;ENVIAR
                                        </button>

                                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                                            Olvidó su contraseña?
                                        </a>


                                    </div>
                                </div>
                            </form>
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
            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    var type = "{{ Session::get('alert-type') }}";
    var text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}
</script>
@endpush