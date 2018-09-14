@extends('layouts.back.master')

@section('page_title','Configuraciones')

{{--@section('breadcrumbs')--}}
    {{--{!! Breadcrumbs::render('categoria') !!}--}}
{{--@stop--}}

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
@endpush

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <div class="pull-left mb-30">
                <h5 class="text-blue">Configuraciones generales</h5>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Empresa *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('empresa',null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">RUC *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('ruc',null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Teléfonos *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('telefonos',null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Email Contacto *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::email('email',null,['class'=>'form-control', 'style'=>'text-transform: lowercase','required','placeholder'=>'email@contacto']) !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-12 col-md-2 col-form-label weight-600">Dirección *</label>
            <div class="col-sm-12 col-md-6">
                {!! Form::text('direccion',null,['class'=>'form-control', 'style'=>'text-transform: uppercase','required','placeholder'=>'email@contacto']) !!}
            </div>
        </div>

    </div>

@endsection

@push('scripts')

<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $('document').ready(function() {
    });


    {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

</script>
@endpush