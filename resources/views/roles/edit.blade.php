@extends('layouts.app')

@section('content')

    <div class='col-lg-4 col-lg-offset-4'>
        <h1><i class='fa fa-key'></i> Editar Role: {{$role->name}}</h1>
        <hr>

        {!! Form::model($role,['route'=>['roles.update',$role],'method'=>'PUT']) !!}

        <div class="form-group">
            {{ Form::label('name', 'Nombre del Rol') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>

        <h5><b>Asignar Permisos</b></h5>
        @foreach ($permissions as $permission)

            {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
            {{Form::label($permission->name, ucfirst($permission->name)) }}<br>

        @endforeach
        <br>
        {{ Form::button('Edit', ['class' => 'btn btn-primary','type'=>'submit']) }}

        {{ Form::close() }}
    </div>

@endsection
