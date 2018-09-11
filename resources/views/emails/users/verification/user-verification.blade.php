{{--Vista que se renderiza si ocurre un error durante la verificacion del usuario--}}
@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Fallo en la verificación</div>
                <div class="panel-body">
                    <span class="help-block">
                        <strong>Su cuenta no pudo ser verificada.</strong>
                    </span>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{{url('/')}}" class="btn btn-primary">
                                Regresar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
