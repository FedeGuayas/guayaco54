@extends('layouts.back.master')

@section('page_title','Editar descuento')

@section('breadcrumbs')
    {!! Breadcrumbs::render('descuento-edit',$descuento) !!}
@endsection

@push('styles')
<!-- bootstrap-touchspin css -->
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Editar Descuento</h5>
            </div>
        </div>
        {!! Form::model($descuento,['route'=>['descuentos.update',$descuento->id],'method'=>'PUT']) !!}

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    {!! Form::label('nombre','Nombre del descuento: *') !!}
                    {!! Form::text('nombre',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                    <small class="form-text text-muted">Ej: Discapacitados, Adulto Mayor, Fecha Inscripción, etc...</small>
                </div>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    {!! Form::label('porciento','Porciento: *') !!}
                    {!! Form::text('porciento',null,['class'=>'form-control porciento_touchspin']) !!}
                </div>
                <small class="form-text text-muted">Ej: 10 = 10%, 50 = 50%</small>
            </div>
        </div>
        <small class="form-text text-red-50">* Campos requeridos</small>
        <div class="col-md-6 mt-15">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i>
                    Guardar
                </button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

@endsection


@push('scripts')
<!-- bootstrap-touchspin js -->
<script src="{{asset('themes/back/src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(".porciento_touchspin").TouchSpin({
        postfix: '%',
        initval: 0,
        min: 0,
        max: 100,
        step: 1,
        buttondown_class: "btn btn-primary",
        buttonup_class: "btn btn-primary"
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