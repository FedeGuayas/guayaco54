@extends('layouts.back.master')

@section('page_title','Comprobantes')

@section('breadcrumbs')
    {!! Breadcrumbs::render('comprobante') !!}
@stop

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet " type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
<link rel="stylesheet " type="text/css"
      href="{{asset('css/my_datatable.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Inscripciones pendientes</h5>
            </div>
        </div>

        {{--@if ( Entrust::hasRole(['export_reserva','administrator']) )--}}
        <div class="row">
            <div class="col s6 right">
                {!! Form::open (['route' => 'admin.reserva.export',	'method' => 'POST', 'autocomplete'=> 'off', 'role' => 'search' ])!!}
                <ul class="collapsible popout" data-collapsible="accordion">
                    <li>
                        <div class="collapsible-header green accent-1"><i class="fa fa-file-excel-o"></i> <b>Exportar
                                preinscripción</b></div>
                        <div class="collapsible-body">
                            <div class="input-field col s4">
                                {!! Form::label('searchDesde','Desde') !!}
                                {!! Form::text('searchDesde',null,['class'=>'validate']) !!}
                            </div>
                            <div class="input-field col s4">
                                {!! Form::label('searchHata','Hasta') !!}
                                {!! Form::text('searchHata',null,['class'=>'validate']) !!}
                            </div>
                            <div class="input-field col s2">
                                {!!   Form::button('<i class="fa fa-file-excel-o" aria-hidden="true"></i>',['type'=>'submit', 'class'=>'btn-floating indigo waves-effect waves-light tooltipped', 'data-position'=>'top', 'delay'=>'50', 'data-tooltip'=>'Exportar']) !!}
                            </div>
                            <br><br><br><br>
                        </div>
                    </li>
                </ul>
                {!! Form::close() !!}
            </div>
        </div>
        {{--@endif--}}

        <div class="pd-20 bg-white border-radius-4 box-shadow">

            <div class="table-responsive">
                <table class="data-table table-striped">
                    <thead>
                    <tr>
                        <th class="datatable-nosort">Acción</th>
                        <th>Reg.</th>
                        <th>Nombres</th>
                        <th class="datatable-nosort">Num. Identidad</th>
                        <th>Categoría</th>
                        <th>Circuito</th>
                        <th width="5">No. Corredor</th>
                        <th class="datatable-nosort">Email</th>
                        <th>Fecha</th>
                        <th>Vence</th>
                        <th>Estado</th>
                        <th>F.Pago</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach ($inscripciones as $insc)
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                       data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        {{--<a class="dropdown-item" href="{{route('categorias.edit',$cat->id)}}"><i--}}
                                        {{--class="fa fa-pencil text-success"></i> Editar</a>--}}
                                        @hasanyrole(['admin','employee'])
                                        @can('delete_reservas')
                                            <a class="dropdown-item"
                                               href="{{ route('admin.reserva.cancel', $insc->id ) }}"
                                               data-position="top" data-toggle="tooltip"
                                               data-tooltip="Cancelar Reserva"><i
                                                        class=" fa fa-trash text-danger" aria-hidden="true"></i>
                                                Cancelar
                                            </a>
                                        @endcan
                                        @can('edit_reservas')
                                            <a class="dropdown-item"
                                               href="{{ route('admin.reserva.confirm', $insc->id ) }}"
                                               data-position="top" data-toggle="tooltip" data-tooltip="Aprobar Reserva">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                Confirmar
                                            </a>
                                            <a class="dropdown-item"
                                               href="{{ route('admin.reserva.edit', $insc->id ) }}"
                                               data-position="top" data-toggle="tooltip"
                                               data-tooltip="Editar Forma Pago">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                Cambiar F. Pago
                                            </a>
                                        @endcan
                                        @endhasanyrole
                                    </div>
                                </div>
                            </td>

                            <td>{{ sprintf("%'.05d",$insc->id) }}</td>
                            <td>{{ $insc->persona->getFullName() }}</td>
                            <td>{{ $insc-> persona->num_doc}}</td>
                            <td>{{ $insc->producto->categoria->categoria }}</td>
                            <td>{{ $insc->producto->circuito->circuito }}</td>
                            <td>{{ $insc->numero }}</td>
                            <td>{{ $insc->persona->email }}</td>
                            <td>{{$insc->created_at->diffForHumans()}}</td>

                            <td>
                                @role('admin')
                                {{ $insc->created_at->addDay()->toDateString() }}</td>
                            @endrole
                            <td>
                                @if (\Carbon\Carbon::now()->diffInHours($insc->created_at)>48)
                                    <span class="red-text">Venc. (+48H)</span>
                                @else
                                    <span class="teal-text">OK</span>
                                @endif

                            </td>
                            <td>
                                {{$insc->factura->mpago->nombre}}
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/responsive.bootstrap4.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

        $(document).ready(function () {

            $('.data-table').DataTable({
                scrollCollapse: true,
                autoWidth: false,
                responsive: true,
                columnDefs: [{
                    targets: "datatable-nosort",
                    orderable: false,
                }],
                "lengthMenu": [[5, 10, -1], [5, 10, "Todos"]],
                "language": {
                    "url": '/plugins/DataTables/i18n/Spanish_original.lang'
                }
            });

        });

</script>
@endpush
