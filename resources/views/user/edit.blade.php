@extends('layouts.back.master')

@section('page_title','Editar usuario')

@section('breadcrumbs')
    {!! Breadcrumbs::render('user-edit',$user) !!}
@endsection

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-15">
            <div class="pull-left">
                <h5 class="text-blue">Editar Usuario</h5>
            </div>
        </div>


        {!! Form::model($user,['route'=>['users.update',$user->id],'method'=>'PUT']) !!}
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    {{ Form::label('first_name', 'Nombres') }}
                    {{ Form::text('first_name', null,['class' => 'form-control','style'=>'text-transform: uppercase']) }}
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group">
                    {{ Form::label('last_name', 'Apellidos') }}
                    {{ Form::text('last_name', null,['class' => 'form-control','style'=>'text-transform: uppercase']) }}
                </div>

            </div>
            <div class="col-md-3 col-sm-6">
                <div class="form-group ">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', null,['class' => 'form-control','style'=>'text-transform: lowercase']) }}
                    <small class="form-text text-danger">Si cambia el email deberá confirmar el nuevo para poder iniciar
                        sesión
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('password', 'Contraseña Nueva') }}<br>
                    {{ Form::password('password', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('password', 'Confirmar Contraseña') }}<br>
                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                </div>
            </div>
            @if ($user->hasRole('employee'))
                <div class="col-md-3 col-sm-6">
                    <div class="form-group ">
                        {{ Form::label('escenario_id', 'Escenario') }}
                        {!! Form::select('escenario_id', $esc_list,$user->escenario, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary']) !!}
                    </div>
                    <small class="form-text text-danger">Punto de cobro</small>
                </div>
            @endif
        </div>

    </div>

    <div class="clearfix mb-15">
        <div class="pull-left">
            <h5 class="text-danger">Roles</h5>
        </div>
    </div>
    <div class="row">
        @foreach ($roles as $role)
            <div class="col-md-3 col-sm-6">
                <div class='form-group'>
                    {{ Form::checkbox('roles[]',  $role->id, $user->roles ) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="clearfix mb-15">
        <div class="pull-left">
            <h5 class="text-danger">Permisos Directos</h5>
        </div>
    </div>
    <div class="row">

        @foreach ($permisos as $per)
            <div class="col-md-3 col-sm-6">
                <div class='form-group'>
                    {{ Form::checkbox('permissions[]' ,$per->id,$user->permissions ) }}
                    {{ Form::label($per->name, ucfirst($per->name)) }}
                </div>
            </div>
        @endforeach

    </div>


    {{ Form::button('Add', ['class' => 'btn btn-primary','type'=>'submit']) }}

    {!! Form::close() !!}

    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>
            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    var type = "{{ Session::get('alert-type') }}";
    var text_toastr = "{{ Session::get('message_toastr') }}";
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