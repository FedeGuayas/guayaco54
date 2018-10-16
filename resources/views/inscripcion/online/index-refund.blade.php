@extends('layouts.back.master')

@section('page_title','Reembolsos')

@section('breadcrumbs')
    {!! Breadcrumbs::render('refund') !!}
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
                <h5 class="text-blue">Pagos realizados con tarjeta que desee reembolsar</h5>
            </div>
        </div>
        <div class="form-text small mb-30">
            <strong> </strong>
        </div>
        <div class="row">
            <div id="loader" hidden>
                <i class="fa fa-spinner fa-pulse fa-5x fa-fw text-success"></i>
                <span class="sr-only">Cargando...</span>
            </div>
            <table class="data-table stripe hover nowrap compact">
                <thead>
                <tr>
                    <th class="datatable-nosort">Acción</th>
                    <th class="datatable-nosort">Reg.</th>
                    <th>Transacción</th>
                    <th>Fecha. Pago</th>
                    <th>Valor</th>
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
                                    <a class="dropdown-item refund" href="#" data-id="{{$c->factura->payment_id}}"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Reembolsar y cancelar Inscripción">
                                            <i class="fa fa-recycle text-danger"></i> Reembolsar
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>{{$c->id}}</td>
                        <td>{{$c->factura->payment_id}}</td>
                        <td>{{$c->factura->updated_at}}</td>
                        <td>$ {{ number_format($c->factura->total,2,'.', ' ') }}</td>
                        <td>
                            @if ( ($c->factura->status===\App\Factura::PAGADA && $c->status===\App\Inscripcion::PAGADA) && !is_null($c->factura->payment_id) && strtolower($c->factura->mpago->nombre)=== 'tarjeta' )
                                <span class="text-success" data-toggle="tooltip" data-placement="left"
                                      title="Confirmada"><i class="fa fa-check-square-o fa-2x"></i></span>
                            @elseif (($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)!= 'tarjeta')
                                <span class="text-danger" data-toggle="tooltip" data-placement="left"
                                      title="Reembolsado"> <i class="fa fa-recycle fa-2x"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {!! Form::open(['method'=>'POST','id'=>'form-refund']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/responsive.bootstrap4.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    let table;


    $('document').ready(function () {

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


    });

    let token = $("input[name=_token]").val();
//

    function genToken(){
        let data;
        let url="{{route('payment.getToken')}}";
        $.ajax({
            url: url,
            data: '',
            async: false,
            headers: {'X-CSRF-TOKEN': token},
            type: "post",
            success: function (response) {
                data=response;
            },
            error: function (error) {
               console.log(error);
            }
        });
        return data;
    }

    //Realizar reembolso
    $(document).on('click', '.refund', function (e) {
        e.preventDefault();
        {{--let client_app_code= "{{ $configuracion->client_app_code }}";--}}
        {{--let client_app_key= "{{ $configuracion->client_app_key }}";--}}
        {{--let unix_timestamp= $.now().toString();--}}
        {{--let uniq_token_string = client_app_key + unix_timestamp;--}}

        let auth_token=genToken();


        let id = $(this).attr('data-id');
        console.log(auth_token)
        let row = $(this).parents('tr');
        let form = $("#form-refund");
        let url = 'https://ccapi-stg.paymentez.com/v2/transaction/refund/';
        let data = {
            "transaction": {
                "id": id
            }
        };
        swal({
            title: 'Confirme la acción',
            text: "Se cancelará la inscripción y se procederá al reembolso de los fondos. Esta acción no se podrá deshacer!",
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
                        headers: {'Auth-Token': auth_token},
                        type: "post",
                        contentType: 'application/json',
                        dataType:'json',
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
            console.log(response)
            //confirmo la acción
            if (response.value) {
//                    console.log(response)
                swal({
                    title: ':)',
                    text: 'Inscripción eliminada, reembolso realizado',
                    type: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((resp) => {
                    if (resp.value) { //recargar al dar en ok
                        row.fadeOut();
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
            row.show();
            swal(
                ':( Lo sentimos ocurrio un error durante su petición',
                '' + error.status + ' ' + error.statusText + '',
                'error'
            )
        });
    });



       //    Fin Paymentez

            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

</script>
@endpush