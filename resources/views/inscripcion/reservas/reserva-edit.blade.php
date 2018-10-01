@extends('layouts.admin.index')

@section('title', 'Inscripción')

@section('content')

    <div class="row">
        <h5 class="header teal-text text-darken-2">Editar Reserva No. {{$inscripcion->id}}</h5>
        @include('alert.request')
        @include('alert.success')
    </div><!--/.row-->

    <div class="row">

        <div class="col s12 m8">

            <table class="table table-striped  table-condensed table-hover highlight responsive-table">
                <tr>
                    <th>Escenario</th>
                    <th>{{ $inscripcion->calendar->program->escenario->escenario }}</th>
                </tr>
                <tr>
                    <th>Modulo</th>
                    <th>{{ $inscripcion->calendar->program->modulo->modulo }}</th>
                </tr>
                <tr>
                    <th>Fecha (Inicio / Fin)</th>
                    <th>{{ $inscripcion->calendar->program->modulo->inicio }} / {{ $inscripcion->calendar->program->modulo->fin }}</th>
                </tr>
                <tr>
                    <th>Disciplina</th>
                    <th>{{ $inscripcion->calendar->program->disciplina->disciplina }}</th>
                </tr>
                <tr>
                    <th>Representante</th>
                    <th>{{ $inscripcion->factura->representante->persona->getNombreAttribute() }}  (Tel: {{ $inscripcion->factura->representante->persona->telefono }})</th>
                </tr>
                <tr>
                    <th>CI Rep.</th>
                    <th>{{ $inscripcion->factura->representante->persona->num_doc }}</th>
                </tr>
                <tr>
                    <th>Alumno</th>
                    <th>
                        @if ($inscripcion->alumno_id == 0)
                            {{$inscripcion->factura->representante->persona->getNombreAttribute()}}
                        @else
                            {{ $inscripcion->alumno->persona->getNombreAttribute()}}
                        @endif
                    </th>
                </tr>
                <tr>
                    <th>Valor</th>
                    <th>$ {{ number_format($inscripcion->factura->total, 2, '.', ' ') }}</th>
                </tr>
                <tr>
                    <th>Creada</th>
                    <th>{{$inscripcion->created_at->diffForHumans()}}</th>
                </tr>
                <tr>
                    <th>Vence</th>
                    <th>{{ $inscripcion->created_at->addDay()->toDateString() }}</th>
                </tr>
                <tr>
                    <th>F. Pago</th>
                    <th>{{$inscripcion->factura->pago->forma}}</th>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <h5 class="header teal-text text-darken-2">Cambiar la forma de pago</h5>
        {!! Form::model($inscripcion,['route'=>['admin.reserva.update',$inscripcion], 'method'=>'PUT'])  !!}
        <div class="input-field col s4 ">
            {!! Form::select('mpago_id', $fpago,$inscripcion->factura->pago_id,['placeholder'=>'selec']) !!}
            {!! Form::label('mpago_id','Forma de Pago:') !!}
        </div>
        <div class="col s6">
            {!! Form::button('Actualizar<i class="fa fa-check right"></i>', ['class'=>'btn waves-effect waves-light','type' => 'submit']) !!}
            <a href="{{ route('admin.inscripcions.reservas') }}" class="tooltipped" data-position="top" data-delay="50"
               data-tooltip="Regresar">
                {!! Form::button('<i class="fa fa-arrow-left"></i>',['class'=>'btn waves-effect waves-light darken-1']) !!}
            </a>
        </div>
        {!! Form::close() !!}
    </div>

@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $("#fpago_id").material_select();
        });
    </script>

@endsection