@extends('layouts.back.master')

@section('page_title','Editar escenario')

@section('breadcrumbs')
    {!! Breadcrumbs::render('escenario-edit',$escenario) !!}
@endsection

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Editar Escenario</h5>
            </div>
        </div>
        {!! Form::model($escenario,['route'=>['escenarios.update',$escenario->id],'method'=>'PUT']) !!}

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="circuito">Escenario:</label>
                    {!! Form::text('escenario',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
                    <small class="form-text text-muted">Nombre del escenario deportivo donde se realizarán inscripciones.</small>
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