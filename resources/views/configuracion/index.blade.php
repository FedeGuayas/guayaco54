@extends('layouts.back.master')

@section('page_title','Configuraciones')

{{--@section('breadcrumbs')--}}
{{--{!! Breadcrumbs::render('categoria') !!}--}}
{{--@stop--}}

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
@endpush

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix">
            <div class="pull-left mb-30">
                <h5 class="text-blue">Configuraciones generales</h5>
            </div>
        </div>

        {!! Form::open(['route' => 'admin.configurations.store', 'method' => 'post', 'autocomplete'=> 'off']) !!}
        {!! Form::hidden('config_id', isset($config->id) ? $config->id : null,['config_id']) !!}
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Año:</label>
            <div class="col-sm-6 col-md-2">
                {!! Form::select('ejercicio_id', $ejercicios, isset($config->ejercicio_id) ? $config->ejercicio_id : null , ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','value'=>'{{ old("ejercicio_id") }}','required']) !!}
                <small class="form-text text-muted">Año activo</small>
            </div>
            <div class="col-sm-6 col-md-2">
                <a href="#modal-year" data-toggle="modal"
                   class="btn btn-outline-primary"><i class="ion-plus"></i> Nuevo
                </a>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Impuesto:</label>
            <div class="col-sm-6 col-md-2">
                {!! Form::select('impuesto_id', $impuestos,isset($config->impuesto_id) ? $config->impuesto_id : null , ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','value'=>'{{ old("impuesto_id") }}','required']) !!}
                <small class="form-text text-muted">Iva actual</small>
            </div>
            <div class="col-sm-6 col-md-2">
                <a href="#modal-iva" data-toggle="modal"
                   class="btn btn-outline-primary"><i class="ion-plus"></i> Nuevo
                </a>
            </div>
        </div>
        <div class="form-group row">
            <label for="client_app_code" class="col-sm-12 col-md-2 col-form-label weight-600">App Code</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('client_app_code',isset($config->client_app_code) ? $config->client_app_code : null,['class'=>'form-control','placeholder'=>'app code paymentez']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label for="client_app_key" class="col-sm-12 col-md-2 col-form-label weight-600">App Key</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('client_app_key',isset($config->client_app_key) ? $config->client_app_key : null,['class'=>'form-control','placeholder'=>'app key paymentez']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Empresa *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('empresa',isset($config->empresa) ? $config->empresa : null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">RUC *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('ruc',isset($config->ruc) ? $config->ruc : null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Teléfonos *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('telefonos',isset($config->telefonos) ? $config->telefonos : null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
                <small class="form-text">Separe los teléfonos con espacios</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Email Contacto *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::email('email',isset($config->email) ? $config->email : null,['class'=>'form-control', 'style'=>'text-transform: lowercase','required','placeholder'=>'email@contacto']) !!}
                <small class="form-text">A este email serán dirigidos los comentarios de los usuarios</small>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Dirección *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('direccion',isset($config->direccion) ? $config->direccion : null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Nombre Contacto *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('nombre_contacto',isset($config->nombre_contacto) ? $config->nombre_contacto : null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i>
                    Guardar
                </button>
                <button type="reset" class="btn btn-outline-danger"><i class="fa fa-ban"></i>
                    Cancelar
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    @include('configuracion.modals.iva')
    @include('configuracion.modals.year')
@endsection

@push('scripts')

<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
            @endif
            {{-- FIN Alertas con Toastr--}}
            {{--errorres de validacion--}}
            @if ($errors->any())
    var errors = [];
    var error = '';
    @foreach ($errors->all() as $error)
errors.push("{{$error}}");
    @endforeach
    if (errors) {
        $.each(errors, function (i) {
            error += errors[i] + '<br>';
        });
    }
    showError(error);
    @endif

</script>
@endpush