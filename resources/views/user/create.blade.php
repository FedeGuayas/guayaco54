@extends('layouts.back.master')

@section('page_title','Crear usuario')

@section('breadcrumbs')
    {!! Breadcrumbs::render('user-create') !!}
@endsection

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-15">
            <div class="pull-left">
                <h5 class="text-blue">Agregar Usuario</h5>
            </div>
        </div>

        {!! Form::open(['route'=>'users.store', 'method'=>'POST','autocomplete'=>'off']) !!}

        <div class="form-group">
            {{ Form::label('first_name', 'Nombres') }}
            {{ Form::text('first_name', null,['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('last_name', 'Apellidos') }}
            {{ Form::text('last_name', null,['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', null, ['class' => 'form-control']) }}
        </div>

        {!! Form::label('roles','Roles') !!}
        <div class='form-group'>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}
            @endforeach
        </div>

        {{--{!! Form::label('permisos','Permisos Directos') !!}--}}
        {{--<div class='form-group'>--}}
            {{--@foreach ($permisos as $per)--}}
                {{--{{ Form::checkbox('permissions[]',  $per->id ) }}--}}
                {{--{{ Form::label($per->name, ucfirst($per->name)) }}--}}
            {{--@endforeach--}}
        {{--</div>--}}

        <div class="form-group">
            {{ Form::label('password', 'Contraseña') }}<br>
            {{ Form::password('password', ['class' => 'form-control']) }}

        </div>

        <div class="form-group">
            {{ Form::label('password', 'Confirmar Contraseña') }}<br>
            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
        </div>

        {{ Form::button('Guardar', ['class' => 'btn btn-primary','type'=>'submit']) }}

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
