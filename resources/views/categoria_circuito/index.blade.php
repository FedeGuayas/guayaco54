@extends('layouts.back.master')

@section('page_title','Categorías / Circuitos')

{{--@section('breadcrumbs')--}}
    {{--{!! Breadcrumbs::render('categoria') !!}--}}
{{--@stop--}}

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
@endpush

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Categorías activas y sus circuitos</h5>
                <p class="font-14">para asociar categorias y circuitos <a class="btn btn-sm btn-outline-primary" href="{{route('categoria-circuito.create')}}">
                        Click <i class="fa fa-plus"></i>
                    </a>
                </p>
            </div>
        </div>
        <div class="row">
            <table class="data-table stripe hover nowrap compact">
                <thead>
                <tr>
                    <th class="datatable-nosort">Categoría</th>
                    <th>Edad Inicio</th>
                    <th>Edad Fin</th>
                    <th>Circuitos</th>
                    <th class="datatable-nosort">Acción</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categorias as $cat)
                    @if (count($cat->circuitos)>0)
                <tr>
                    <td class="dt-nosort">{{$cat->categoria}}</td>
                    <td>{{$cat->edad_start}}</td>
                    <td>{{$cat->edad_end}}</td>
                    <td class="dt-nosort">
                        @foreach($cat->circuitos as $cir)
                            {{$cir->circuito.' '}}
                        @endforeach

                    </td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{route('categoria-circuito.edit',$cat->id)}}"><i class="fa fa-recycle text-success"></i> Actualizar</a>
                            </div>
                        </div>
                    </td>
                </tr>
                    @endif
@endforeach
                </tbody>
            </table>
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

    $('document').ready(function() {
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
                "url": '/guayaco-runner/plugins/DataTables/i18n/Spanish_original.lang'
            }
        });
    });


    {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

</script>
@endpush