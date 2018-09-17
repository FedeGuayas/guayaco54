@extends('layouts.back.master')

@section('page_title','Categorías / Circuitos')


@push('styles')
<!-- bootstrap-touchspin css -->
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css')}}">
@endpush

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">

        {!! Form::open(['route' => 'productos.store', 'method' => 'post', 'autocomplete'=> 'off','id'=>'form' ]) !!}
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Crear circuitos a las categorías</h5>
            </div>
            <div class="form-group col-2 pull-right">
                {!! Form::text('anio', $config->ejercicio->year, ['class'=>'form-control','disabled']) !!}
                {!! Form::hidden('ejercicio_id', $config->ejercicio_id, ['id'=>'ejercicio_id']) !!}

            </div>
        </div>

        <div class="row">

            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="categoria_id" class="weight-600">Categorías:</label>
                    {!! Form::select('categoria_id', $categorias,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','value'=>'{{ old("categoria_id") }}','required']) !!}
                    <small class="form-text text-muted">Seleccione la categoría</small>
                </div>
            </div>

            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <label class="weight-600">Circuitos</label>
                    @if(!$circuitos->isEmpty())
                        @foreach ($circuitos as $cir)
                            <div class="custom-control custom-radio mb-5">
                                {{ Form::radio('circuito',  $cir->id,false, ['id'=>$cir->id,'class'=>'custom-control-input'] ) }}
                                {{ Form::label($cir->id, ucfirst($cir->circuito),['class'=>'custom-control-label']) }}
                                {{--<label class="custom-control-label" for="customRadio4">Toggle this custom radio</label>--}}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <div class="form-group">
                    <label for="price" class="weight-600">Costo</label>
                    {!! Form::text('price',null,['class'=>'price_touchspin']) !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="description" class="weight-600">Descripción:</label>
                    {!! Form::textarea('description',null,['style'=>'text-transform: uppercase; height:80px; width: 100%', 'class'=>'form-control']) !!}
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
                <a href="{{route('productos.index')}}" class="btn btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@endsection


@push('scripts')
<script src="{{asset('themes/back/src/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(".price_touchspin").TouchSpin({
        initval: 10,
        min: 0,
        max: 100,
        step: 0.1,
        decimals: 2,
        boostat: 5,
        maxboostedstep: 10,
        prefix: '$',
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