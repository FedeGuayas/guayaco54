@extends('layouts.back.master')

@section('page_title','Crear talla')

@section('breadcrumbs')
    {!! Breadcrumbs::render('talla-create') !!}
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
                <h5 class="text-blue">Nueva Talla</h5>
            </div>
        </div>

        {!! Form::open(['route' => 'tallas.store', 'method' => 'post', 'autocomplete'=> 'off', 'class'=>'','id'=>'form' ]) !!}
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="circuito">Talla:</label>
                    {!! Form::text('talla',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                    <small class="form-text text-muted">Ej: 26, 28, 30, etc...</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    <label for="color">Color</label>
                    {!! Form::select('color', ['n'=> 'NEGRA', 'b' => 'BLANCA'],null, ['class'=>'form-control','required']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-6">
                <div class="form-group">
                    <div class="form-group">
                        <label>Stock</label>
                        {!! Form::text('stock',null,['class'=>'stock_touchspin']) !!}
                    </div>
                </div>
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
                <a href="{{route('tallas.index')}}" class="btn btn-outline-secondary"><i
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

    $(".stock_touchspin").TouchSpin({
        initval: 0,
        min: 0,
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