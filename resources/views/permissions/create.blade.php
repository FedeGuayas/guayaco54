@extends('layouts.back.master')

@section('page_title','Crear permiso')

@section('breadcrumbs')
    {!! Breadcrumbs::render('permiso-create') !!}
@endsection


@section('content')


    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue"><i class='fa fa-key'></i> Crear Permiso</h5>
            </div>
        </div>

        {!! Form::open(['route' => 'permissions.store', 'method' => 'post', 'autocomplete'=> 'off', 'id'=>'form' ]) !!}
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="name">Nombre del permiso:</label>
                    {!! Form::text('name',null,['class'=>'form-control','style'=>'text-transform: lowercase','required']) !!}
                    <small class="form-text text-muted">Ej: view_report, add_article, edit_report, delete_record ...
                    </small>
                </div>
            </div>

        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label class="weight-600">Asigar el Permiso al Rol:</label>
                    @if(!$roles->isEmpty())
                        @foreach ($roles as $role)
                            <div class="custom-control custom-checkbox mb-5">
                                {{ Form::checkbox('roles[]',  $role->id,false, ['id'=>$role->id,'class'=>'custom-control-input'] ) }}
                                {{ Form::label($role->id, ucfirst($role->name),['class'=>'custom-control-label']) }}
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
                <a href="javascript:history.go(-1)" class="btn btn-outline-secondary"><i
                            class="fa fa-arrow-left"></i>
                    Regresar
                </a>
            </div>
        </div>

        {{ Form::close() }}

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