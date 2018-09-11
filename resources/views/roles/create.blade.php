@extends('layouts.back.master')

@section('page_title','Crear rol')

@section('breadcrumbs')
    {!! Breadcrumbs::render('role-create') !!}
@endsection


@section('content')


    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue"><i class='fa fa-key'></i> Crear Rol</h5>
            </div>
        </div>
        {!! Form::open(['route'=>'roles.store', 'method'=>'POST','autocomplete'=> 'off']) !!}
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="name">Nombre del Rol: * </label>
                    {!! Form::text('name',null,['class'=>'form-control','style'=>'text-transform: lowercase','required']) !!}
                    <small class="form-text text-muted">Ej: admin, gestor, guest, etc ...
                    </small>
                </div>
            </div>

        </div>

        <div class="form-group">
            <label class="weight-600">Asigar Permisos al ROl:</label>
            <div class="row">

                @if(!$permissions->isEmpty())
                    @foreach ($permissions as $permission)
                        <div class="col-md-3">
                            <div class="custom-control custom-checkbox mb-5">
                                {{ Form::checkbox('permissions[]',  $permission->id,false, ['id'=>$permission->id,'class'=>'custom-control-input']) }}
                                {{ Form::label($permission->id, ucfirst($permission->name),['class'=>'custom-control-label']) }}
                            </div>
                        </div>
                    @endforeach
                @endif
                {{--</div>--}}
            </div>
        </div>

        <small class="form-text text-danger">* Campos obligatorios</small>

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

            {{--errorres de validacion--}}
            @if ($errors->any())
    let errors = [];
    let error = '';
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