@extends('layouts.back.master')

@section('page_title','Editar categoría')

@section('breadcrumbs')
    {!! Breadcrumbs::render('categoria-edit',$categoria) !!}
@endsection

@push('styles')
<!-- bootstrap-touchspin css -->
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Editar Categoría</h5>
            </div>
        </div>
        {!! Form::model($categoria,['route'=>['categorias.update',$categoria->id],'method'=>'PUT']) !!}

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="circuito">Categoría:</label>
                    {!! Form::text('categoria',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                    <small class="form-text text-muted">Ej: Niños, Discapacitado, Abierta, etc...</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6">

                <div class="form-group">
                    <label for="edad_start">Edad inicio</label>
                    {!! Form::text('edad_start',null,['class'=>'edad_touchspin']) !!}
                </div>
                <small class="form-text text-muted">Ej: 0 sino es requerida o para menos de 1 año</small>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="form-group">
                    <div class="form-group">
                        <label>Edad fin</label>
                        {!! Form::text('edad_end',null,['class'=>'edad_touchspin']) !!}
                    </div>
                </div>
                <small class="form-text text-muted">Ej: 0 sino es requerida</small>
            </div>
        </div>

        <div class="col-md-6 mt-15">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i>
                    Guardar
                </button>
                <button type="reset" class="btn btn-outline-danger"><i class="fa fa-ban"></i>
                    Cancelar
                </button>
                <a href="javascript:history.go(-1)" class="btn btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i>
                    Regresar
                </a>
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

    $(".edad_touchspin").TouchSpin({
        initval: 0,
        min: 0,
        max: 100,
        step: 1,
        buttondown_class: "btn btn-outline-primary",
        buttonup_class: "btn btn-outline-primary"
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