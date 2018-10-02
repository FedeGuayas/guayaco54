@extends('layouts.back.master')

@section('page_title','Reservas')

@section('breadcrumbs')
    {!! Breadcrumbs::render('reserva-edit',$inscripcion) !!}
@stop

@section('content')

    <div class="col-md-12 mb-30">
        <div class="pd-20 bg-white border-radius-4 mb-30">
            <div class="clearfix mb-20">
                <div class="pull-left">
                    <h5 class="text-blue">Detalles de la reserva No. {{sprintf("%'.04d", $inscripcion->id)}}</h5>
                </div>

            </div>
            <div class="row">

                <div class="col-md-6 col-sm-12 mb-30">
                    <div class="pd-20 bg-light text-danger border-dark border-radius-4 box-shadow">
                        <div class="table-responsive">
                            <table class="data-table table">
                                <tr>
                                    <td>Categoría</td>
                                    <th>{{ $inscripcion->producto->categoria->categoria }}</th>
                                </tr>
                                <tr>
                                    <td>Circuito</td>
                                    <th>{{ $inscripcion->producto->circuito->circuito }}</th>
                                </tr>
                                <tr>
                                    <td>Fecha Insc.</td>
                                    <th>{{ $inscripcion->created_at->formatLocalized('%d %B %Y')}}</th>
                                </tr>
                                <tr>
                                    <td>Corredo</td>
                                    <th>{{ $inscripcion->persona->getFUllName()}}</th>
                                </tr>
                                <tr>
                                    <td>Identificación</td>
                                    <th>{{ $inscripcion->persona->num_doc }}</th>
                                </tr>
                                <tr>
                                    <td>Costo</td>
                                    <th>$ {{ number_format($inscripcion->factura->total, 2, '.', ' ') }}</th>
                                </tr>
                                <tr>
                                    <td>Hace</td>
                                    <th>{{$inscripcion->created_at->diffForHumans()}}</th>
                                </tr>
                                <tr>
                                    <td>Vence</td>
                                    <th>{{ $inscripcion->created_at->addDay()->formatLocalized('%d %B %Y') }}</th>
                                </tr>
                                <tr>
                                    <td>F. Pago</td>
                                    <th>{{$inscripcion->factura->mpago->nombre}}</th>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">

                    <h5 class="text-info mb-20" >Cambiar la forma de pago</h5>

                    {!! Form::model($inscripcion,['route'=>['admin.reserva.update',$inscripcion->id], 'method'=>'PUT'])  !!}

                    <div class="col">
                        <div class="form-group">

                            {!! Form::select('mpago_id', $mpago,$inscripcion->factura->mpago_id, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'mpago_id','data-container'=>'.main-container','placeholder'=>'Seleccione Forma de pago']) !!}
                            <small class="form-text text-muted"> Modifique la forma de pago</small>
                        </div>
                    </div>

                    <div class="col mt-15">
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Actualizar">
                                <i class="fa fa-save"></i>
                            </button>
                            <a href="{{ route('admin.inscripcions.reservas') }}" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Regresar">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

            </div>


        </div>
    </div>
    </div>


@endsection

@push('scripts')

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