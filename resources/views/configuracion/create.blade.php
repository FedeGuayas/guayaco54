@extends('layouts.back.master')

@section('page_title','Categorías / Circuitos')

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Vincular circuitos a las categorías</h5>
            </div>
        </div>

        {!! Form::open(['route' => 'categoria-circuito.store', 'method' => 'post', 'autocomplete'=> 'off','id'=>'form' ]) !!}
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label for="categoria_id" class="weight-600">Categorías:</label>
                    {!! Form::select('categoria_id', $categorias,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','value'=>'{{ old("categoria_id") }}','required']) !!}
                    <small class="form-text text-muted">Seleccione la categoría</small>
                </div>
            </div>
            <div class="col-md-6 offset-md-1 col-sm-12">
                <div class="form-group">
                    <label class="weight-600">Asociar circuitos a la categoría seleccionada:</label>
                    @if(!$circuitos->isEmpty())
                        @foreach ($circuitos as $cir)
                            <div class="col-md-2">
                            <div class="custom-control custom-checkbox mb-5">
                                {{ Form::checkbox('circuitos_id[]',  $cir->id,false, ['id'=>$cir->id,'class'=>'custom-control-input'] ) }}
                                {{ Form::label($cir->id, ucfirst($cir->circuito),['class'=>'custom-control-label']) }}
                            </div>
                            </div>
                        @endforeach
                    @endif
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
                <a href="{{route('categoria-circuito.index')}}" class="btn btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

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


</script>

@endpush