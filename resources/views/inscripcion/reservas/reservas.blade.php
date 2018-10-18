@extends('layouts.back.master')

@section('page_title','Reservas')

@section('breadcrumbs')
    {!! Breadcrumbs::render('reserva') !!}
@stop

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet " type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
<link rel="stylesheet " type="text/css" href="{{asset('css/my_datatable.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Inscripciones pendientes</h5>
            </div>

            @can('delete_reservas')
                <div class="col-md-6 pull-right">
                    {!! Form::open (['route' => 'admin.reserva.export',	'method' => 'POST', 'autocomplete'=> 'off', 'role' => 'search' ])!!}
                    <div class="form-group row">
                        <div class="col">
                            {!! Form::label('desde','Desde',['class'=>'weight-600']) !!}
                            {!! Form::text('desde',null,['class'=>'form-control date-picker','placeholder'=>'YYYY-MM-DD','value'=>'{{ old("fecha") }}', 'data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-clear-button'=>' true','data-position'=>'right top','id'=>'desde','readonly']) !!}
                        </div>
                        <div class="col">
                            {!! Form::label('hasta','Hasta',['class'=>'weight-600']) !!}
                            {!! Form::text('hasta',null,['class'=>'form-control date-picker','placeholder'=>'YYYY-MM-DD','value'=>'{{ old("fecha") }}', 'data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-clear-button'=>' true','data-position'=>'right top','id'=>'hasta','readonly']) !!}
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-outline-success" data-toggle="tooltip"
                                    data-placement="left" title="Formato Western"><i class="fa fa-file-excel-o"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            @endcan

        </div>


        <div class="pd-20 bg-white border-radius-4 box-shadow">

            <div class="table-responsive">
                <table class="data-table table table-striped nowrap">
                    <thead>
                    <tr>
                        <th class="datatable-nosort">Acción</th>
                        <th width="50">Reg.</th>
                        <th>Nombres</th>
                        <th class="datatable-nosort">Ident.</th>
                        <th>F.Pago</th>
                        <th>Cat.</th>
                        <th>Cir.</th>
                        <th>Fecha</th>
                        <th>Desde</th>
                        <th>Vence</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th class="tfoot_search">No.</th>
                        <th class="tfoot_search">Nombre</th>
                        <th class="tfoot_search">Ident.</th>
                        <th class="tfoot_select"></th>
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
                                               data-toggle="tooltip" data-placement="top" data-delay="50"
                                               title="Eliminar Reserva"><i
                                                        class=" fa fa-trash text-danger" aria-hidden="true"></i>
                                                Eliminar
                                            </a>
                                        @endcan
                                        @can('add_reservas')
                                            <a class="dropdown-item"
                                               href="{{ route('admin.reserva.confirm', $insc->id ) }}"
                                               data-toggle="tooltip" data-placement="top" data-delay="50"
                                               title="Aprobar Reserva">
                                                <i class="fa fa-check text-primary" aria-hidden="true"></i>
                                                Confirmar
                                            </a>
                                        @endcan
                                        @can('edit_reservas')
                                            <a class="dropdown-item"
                                               href="{{ route('admin.reserva.edit', $insc->id ) }}"
                                               data-placement="top" data-toggle="tooltip" data-delay="50"
                                               title="Editar Forma Pago">
                                                <i class="fa fa-edit text-success" aria-hidden="true"></i>
                                                Cambiar F. Pago
                                            </a>
                                        @endcan
                                        @endhasanyrole
                                    </div>
                                </div>
                            </td>

                            <td>{{ sprintf("%'.04d",$insc->id) }}</td>
                            <td>{{ $insc->persona->getFullName() }}</td>
                            <td>{{ $insc-> persona->num_doc}}</td>
                            <td>{{$insc->factura->mpago->nombre}}</td>
                            <td>{{ $insc->producto->categoria->categoria }}</td>
                            <td>{{ $insc->producto->circuito->circuito }}</td>
                            <td>{{$insc->created_at}}</td>
                            <td>{{$insc->created_at->diffForHumans()}}</td>
                            <td>
                                @role('admin')
                                    {{ $insc->created_at->addDay()->toDateString() }}
                                @endrole
                            </td>
                            <td>
                                @if (\Carbon\Carbon::now()->diffInHours($insc->created_at)>48)
                                    <span class="text-danger" data-toggle="tooltip" data-placement="left"
                                          title="Vencida (+48H)"> <i class="fa fa-trash-o fa-2x"></i></span>
                                @else
                                    <span class="text-success" data-toggle="tooltip" data-placement="left"
                                          title="En tiempo"><i class="fa fa-check-square-o fa-2x"></i></span>
                                @endif
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

        $('.tfoot_search').each(function () {
            let title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder=" ' + title + '" />');
        });

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
            },
            initComplete: function (settings, json) {
                $('.data-table').fadeIn();
                this.api().columns().every(function () {
                    let column = this;
                    //input text
                    if ($(column.footer()).hasClass('tfoot_search')) {
                        //aplicar la busquedad
                        let that = this;
                        $('input', this.footer())
                            .on('change', function () {//keypress keyup
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });

                    }
                    else if ($(column.footer()).hasClass('tfoot_select')) { //select
                        // Generar select
                        let select = $('<select class="form-control"><option value="">Seleccione ...</option></select>')
                            .appendTo($(column.footer()).empty())
                            // Buscar cuando el select cambia
                            .on('change', function () {
                                let val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    }
                });
            }
        });

    });

            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    var type = "{{ Session::get('alert-type') }}";
    var text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

</script>
@endpush
