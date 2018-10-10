@extends('layouts.back.master')

@section('page_title','Comprobantes')

@section('breadcrumbs')
    {!! Breadcrumbs::render('comprobante-online') !!}
@endsection

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
<link rel="stylesheet " type="text/css" href="{{asset('css/my_datatable.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Todos sus comprobantes</h5>
            </div>
        </div>
        <div class="form-text small mb-30">En Acción podrá imprimir su comprobante de inscripción, que le permitirá
            realizar el pago o el registro de la inscripción una vez haya realizado el pago. Con este último podrá
            retirar el kit para participar en la carrera.
            <br>
            <strong>Pasas 48 horas de realizada la inscripción sino realiza el pago de la misma esta será eliminada del
                sistema. </strong>
        </div>
        <div class="row">
            <table class="data-table stripe hover nowrap compact">
                <thead>
                <tr>
                    <th class="datatable-nosort">Acción</th>
                    <th class="datatable-nosort">Reg.</th>
                    <th>Inscrito</th>
                    <th>Circuito</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Valor</th>
                    <th>Forma Pago</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comprobantes as $c)
                    <tr>
                        <td class="dt-nosort">
                            <div class="dropdown">
                                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu dropdown-menu-left">
                                    @if ( ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)!= 'tarjeta')
                                        <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top"
                                           title="Imprimir Comprobante" target="_blank">
                                            <i class="fa fa-file-pdf-o text-primary"></i> Imprimir
                                        </a>
                                    @elseif(($c->factura->status===\App\Factura::PAGADA && $c->status===\App\Inscripcion::PAGADA))
                                        <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top"
                                           title="Imprimir Registro" target="_blank">
                                            <i class="fa fa-file-pdf-o text-primary"></i> Imprimir
                                        </a>
                                    @endif
                                    <a class="dropdown-item delete" href="#" data-id="{{$c->id}}" data-toggle="tooltip"
                                       data-placement="top" title="Eliminar">
                                        <i class="fa fa-trash-o text-danger"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>{{$c->id}}</td>
                        <td>{{$c->persona->getFullName()}}</td>
                        <td>{{$c->producto->circuito->circuito}}</td>
                        <td>{{$c->producto->categoria->categoria}}</td>
                        <td>{{$c->created_at}}</td>
                        <td>$ {{ number_format($c->factura->total,2,'.', ' ') }}</td>
                        <td>{{$c->factura->mpago->nombre}}</td>
                        <td>
                            @if (\Carbon\Carbon::now()->diffInHours($c->created_at)>48)
                                <span class="text-danger" data-toggle="tooltip" data-placement="left"
                                      title="Vencida (+48H)"> <i class="fa fa-trash-o fa-2x"></i></span>
                            @else
                                @if ( ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)== 'tarjeta')
                                    <button class="btn btn-outline-success btn-sm js-paymentez-checkout"
                                            data-toggle="tooltip" data-placement="top" title="Proceder al pago">Pagar
                                    </button>
                                @else
                                    <span class="text-success" data-toggle="tooltip" data-placement="left"
                                          title="En tiempo"><i class="fa fa-check-square-o fa-2x"></i></span>
                                @endif
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {!! Form::open(['route'=>['circuitos.set.status',':ID'],'method'=>'post','id'=>'form-status']) !!}
    {!! Form::close() !!}

    {!! Form::open(['route'=>['inscription.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/responsive.bootstrap4.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script src="https://cdn.paymentez.com/checkout/1.0.1/paymentez-checkout.min.js"></script>
<script src="{{asset('plugins/paymentez/paymentez-check-out.js')}}"></script>
<script>

    $('document').ready(function () {

        cargarDatatables();


    });


let  table;
    function cargarDatatables() {


        table = $('.data-table').DataTable({
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
            }
        });
    }



    //Eliminar inscripcion
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-delete");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se eliminará la inscripción, esta acción no se podrá deshacer!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, proceder! <i class="fa fa-trash-o"></i>',
            cancelButtonText: 'No, cancelar! <i class="fa fa-ban"></i>',
            showCloseButton: true,
            confirmButtonClass: 'btn btn-outline-danger m5',
            cancelButtonClass: 'btn btn-outline-secondary m-5',
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: function () {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: url,
                        data: data,
                        headers: {'X-CSRF-TOKEN': token},
                        type: "post",
                        success: function (response) {
                            resolve(response);
                        },
                        error: function (error) {
                            reject(error)
                        }
                    });
                })
            },
        }).then((response) => { //respuesta ajax
            //confirmo la acción
            if (response.value) {
//                    console.log(response)
                swal({
                    title: ':)',
                    text: 'Inscripción eliminada',
                    type: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((resp) => {
                    if (resp.value) { //recargar al dar en ok

                        table.draw();
//                        table.ajax.reload();
                    }
                })
                //cancelo la eliminacion
            } else if (response.dismiss === swal.DismissReason.cancel) {// 'cancel', 'overlay', 'close', and 'timer'
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


    //VERIFICAR
    $(document).on('click', '.status_circuito', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-status");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se Habilitará/Dashabilitará el circuito según corresponda!",
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
            allowEscapeKey: false,
            preConfirm: function () {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: url,
                        data: data,
                        headers: {'X-CSRF-TOKEN': token},
                        type: "post",
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
                    allowEscapeKey: false
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