@extends('layouts.back.master')

@section('page_title','Categorías / Circuitos')

{{--@section('breadcrumbs')--}}
{{--{!! Breadcrumbs::render('categoria') !!}--}}
{{--@stop--}}

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
@endpush

@section('content')

    <div class="pd-20 mt-15 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Productos</h5>
                <p class="font-14">para asociar categorias y circuitos (crear productos)<a
                            class="btn btn-sm btn-outline-primary" href="{{route('productos.create')}}">
                        Click <i class="fa fa-plus"></i>
                    </a>
                </p>
            </div>
        </div>
        <div class="row">
            <table class="data-table stripe hover nowrap compact">
                <thead>
                <tr>
                    <th >Categoría</th>
                    <th>Edad Inicio</th>
                    <th>Edad Fin</th>
                    <th>Circuito</th>
                    <th>Costo</th>
                    <th>Año</th>
                    <th class="datatable-nosort">Estado</th>
                    <th class="datatable-nosort">Acción</th>
                </tr>
                </thead>
                <tbody>
                @if (count($productos)>0)
                    @foreach($productos as $prod)
                        <tr>
                            <td class="dt-nosort">{{$prod->categoria->categoria}}</td>
                            <td>{{$prod->categoria->edad_start}}</td>
                            <td>{{$prod->categoria->edad_end}}</td>
                            <td>{{$prod->circuito->circuito}}</td>
                            <td>{{$prod->price}}</td>
                            <td>{{$prod->ejercicio->year}}</td>
                            <td>
                                @if ($prod->status==\App\Producto::ACTIVO)
                                    <div class="dropdown">
                                        <span class="badge badge-pill badge-success">Activo</span>
                                        <a class="btn btn-outline-success dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item status_producto" href="#" data-id="{{$prod->id}}"><i class="fa fa-check-square-o text-danger"></i> Deshabilitar</a>
                                        </div>
                                    </div>
                                @elseif($prod->status==\App\Producto::INACTIVO)
                                    <div class="dropdown">
                                        <span class="badge badge-pill badge-danger">Inactivo</span>
                                        <a class="btn btn-outline-danger dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item status_producto" href="#" data-id="{{$prod->id}}"><i class="fa fa-check-square-o text-danger"></i> Habilitar</a>
                                        </div>
                                    </div>
                                @endif

                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                       data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{route('productos.edit',$prod->id)}}"><i
                                                    class="fa fa-edit text-success"></i> Editar</a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>

    {!! Form::open(['route'=>['productos.destroy',':ID'],'method'=>'post','id'=>'form-status']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/responsive.bootstrap4.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $('document').ready(function () {
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

    //habilitar /deshabilitar categoria
    $(document).on('click', '.status_producto', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-status");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se Habilitará/Dashabilitará el producto!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, proceder! <i class="fa fa-thumbs-up"></i>',
            cancelButtonText: 'No, cancelar! <i class="fa fa-thumbs-o-down"></i>',
            showCloseButton: true,
            confirmButtonClass: 'btn btn-outline-primary m5',
            cancelButtonClass: 'btn btn-outline-secondary m-5',
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey:false,
            preConfirm: function () {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: url,
                        data: data,
                        headers: {'X-CSRF-TOKEN': token},
                        type: "delete",
                        success: function (response) {
                            resolve(response);
                        },
                        error: function (error) {
                            reject(error)
                        }
                    });
                })
            },
        }).then((result) => { //respuesta ajax
            //confirmo la acción
            if (result.value) {
                swal({
                    title: ':)',
                    text: 'Estado actualizado correctamente',
                    type: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey:false
                }).then((resp) => {
                    if (resp.value) { //recargar al dar en ok
                        window.setTimeout(function () {
                            location.reload()
                        }, 1);
                    }
                })
                //cancelo la eliminacion
            } else if (result.dismiss === swal.DismissReason.cancel) {// 'cancel', 'overlay', 'close', and 'timer'
                swal(
                    'Acción cancelada',
                    'Ud canceló la acción, no se realizaron cambios :)',
                    'error'
                )
            }
        }).catch((error) => { //error en la respuesta ajax
            swal(
                ':( Lo sentimos ocurrio un error durante su petición',
                '' + error.status + ' ' + error.statusText + '',
                'error'
            )
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