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
<script src="https://cdn.paymentez.com/checkout/1.0.1/paymentez-checkout.min.js"></script>

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
            <div id="loader" hidden>
                <i class="fa fa-spinner fa-pulse fa-5x fa-fw text-success"></i>
                <span class="sr-only">Cargando...</span>
            </div>
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
                                    @if ( (\Carbon\Carbon::now()->diffInHours($c->created_at)>48) && $c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA )
                                        <a class="dropdown-item delete" href="#" data-id="{{$c->id}}"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Eliminar">
                                            <i class="fa fa-trash-o text-danger"></i> Cancelar
                                        </a>
                                    @elseif  ((\Carbon\Carbon::now()->diffInHours($c->created_at)< 48) && ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)!= 'tarjeta')
                                        <a class="dropdown-item" href="{{route('user.comprobantePDF',$c->id)}}"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Comprobante" target="_blank">
                                            <i class="fa fa-file-pdf-o text-primary"></i> Imprimir Comprobante
                                        </a>
                                        <a class="dropdown-item delete" href="#" data-id="{{$c->id}}"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Eliminar">
                                            <i class="fa fa-trash-o text-danger"></i> Cancelar
                                        </a>
                                    @elseif ( (\Carbon\Carbon::now()->diffInHours($c->created_at)< 48) && ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)== 'tarjeta')
                                        <a class="dropdown-item delete" href="#" data-id="{{$c->id}}"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Eliminar">
                                            <i class="fa fa-trash-o text-danger"></i> Cancelar
                                        </a>
                                    @elseif (($c->factura->status===\App\Factura::PAGADA && $c->status===\App\Inscripcion::PAGADA))
                                        <a class="dropdown-item" href="{{route('user.registroInscripcion',$c->id)}}"
                                           data-toggle="tooltip" data-placement="top"
                                           title="Confirmación" target="_blank">
                                            <i class="fa fa-file-pdf-o text-primary"></i> Imprimir Registro
                                        </a>
                                    @endif

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
                            @if ( (\Carbon\Carbon::now()->diffInHours($c->created_at)>=48) && ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA))
                                <span class="text-danger" data-toggle="tooltip" data-placement="left"
                                      title="Vencida (+48H)"> <i class="fa fa-trash-o fa-2x"></i>
                                </span>
                            @elseif ((\Carbon\Carbon::now()->diffInHours($c->created_at)< 48) && ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)!= 'tarjeta')
                                <span class="text-warning" data-toggle="tooltip" data-placement="left"
                                      title="En tiempo"><i class="fa fa-hourglass fa-2x"></i></span>
                            @elseif ( (\Carbon\Carbon::now()->diffInHours($c->created_at)< 48) && ($c->factura->status===\App\Factura::PENDIENTE && $c->status===\App\Inscripcion::RESERVADA) && strtolower($c->factura->mpago->nombre)== 'tarjeta')
                                <button class="btn btn-outline-primary btn-sm js-paymentez-checkout"
                                        data-id="{{$c->id}}"
                                        data-toggle="tooltip" data-placement="top" title="Proceder al pago"><i
                                            class="fa fa-money"></i> Pagar
                                </button>
                            @elseif (($c->factura->status===\App\Factura::PAGADA && $c->status===\App\Inscripcion::PAGADA))
                                <span class="text-success" data-toggle="tooltip" data-placement="left"
                                      title="Confirmada"><i class="fa fa-check-square-o fa-2x"></i></span>
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {!! Form::open(['route'=>['inscription.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/responsive.bootstrap4.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script src="https://cdn.paymentez.com/js/ccapi/stg/paymentez.min.js"></script>

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


    //Eliminar inscripcion
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let row = $(this).parents('tr');
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

    //    Paymentez
    let paymentezCheckout = new PaymentezCheckout.modal({
        client_app_code: 'FEDE-EC-CLIENT', // Client Credentials Provied by Paymentez
        client_app_key: 'a8N2cTAlauosoRDxM2mPYbdnW9ALmP', // Client Credentials Provied by Paymentez
        locale: 'es', // User's preferred language (es, en, pt). English will be used by default.
        env_mode: 'stg', // `prod`, `stg`, `dev`, `local` to change environment. Default is `stg`
        onOpen: function () {  //The callback to invoke when Checkout is opened
            // console.log('modal open');
        },
        onClose: function () { //The callback to invoke when Checkout is closed
             console.log('modal closed');
        },
        onResponse: function (response) { // The callback to invoke when the Checkout process is completed
            /*
             In Case of an error, this will be the response.
             response = {
             "error": {
             "type": "Server Error",
             "help": "Try Again Later",
             "description": "Sorry, there was a problem loading Checkout."
             }
             }

             When the User completes all the Flow in the Checkout, this will be the response.
             response = {
             "transaction":{
             "status":"success", // success or failure
             "id":"CB-81011", // transaction_id
             "status_detail":3 // for the status detail please refer to: https://paymentez.github.io/api-doc/#status-details
             }
             }
             */
            // console.log('modal response');

            if (response.transaction.status === 'success' && response.transaction.status_detail === 3) {
                swal({
                    title: ':) Transacción satisfactoria',
                    text: ' Se realizó el pago correctamente',
                    type: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((resp) => {
                    if (resp.value) { //recargar al dar en ok
//                        row.fadeOut();
                        console.log('enviar correo');
                        window.setTimeout(function () {
                            location.reload()
                        }, 1);

                    }
                })

            } else if (response.transaction.status === 'failure' || response.transaction.status === 'pending') {
                let message_error;
                switch (response.transaction.status_detail) {
                    case 9 : //Rechazada
                        message_error = 'Transacción denegada';
                        break;
                    case 1 : //Pendiente
                        message_error = 'Transacción revisada';
                        break;
                    case 11 : //Rechazada, fraude
                        message_error = 'Rechazado por transacción de sistema de fraude';
                        break;
                    case 12 : //Rechazada
                        message_error = 'Tarjeta en lista negra';
                        break;
                    default:
                        message_error = 'No se pudo realizar el pago';
                }
                // console.log(message_error);
                swal(
                    ':( Lo sentimos ocurrio un error durante la transacción',
                    ' Inténtelo mas tarde y si los problemas persisten, puede pagar en uno de nuestros centros de inscripción',
                    'error'
                )
            }

            // document.getElementById('response').innerHTML = JSON.stringify(response);
        }
    });

    let btnOpenCheckout = $(".js-paymentez-checkout");

    if (btnOpenCheckout !== null) {
        $(document).on('click', '.js-paymentez-checkout', function () {
            let id = $(this).data("id");
            let token = $("input[name=_token]").val();
            let url = "{{route('user.getInscripcionPay')}}";
            let data = {
                insc_id: id
            };
            let promise = new Promise((resolve, reject) => {
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
            });
            promise.then((response) => {
                // Open Checkout with further options:
                paymentezCheckout.open({
                    user_id: response.data.user_online.toString(), //id de usuario
                    user_email: response.data.factura.email ? response.data.factura.email : '', //correo facturacion
                    user_phone: response.data.factura.telefono ? response.data.factura.telefono : '', //telefono facturacion
                    order_description: 'Guayaco Runner 2018', //descripcion de la compra
                    order_amount: response.data.factura.total, //monto del pago
                    order_vat: 0, //impuestos
                    order_reference: response.data.id.toString(), //orden de compra (inscripcion_id)
//                    order_installments_type: 2, // optional: The installments type are only available for Equador. The valid values are: https://paymentez.github.io/api-doc/#installments-type
                    //order_taxable_amount: 0, // optional: Only available for Datafast (Equador). The taxable amount, if it is zero, it is calculated on the total. Format: Decimal with two fraction digits.
                    //order_tax_percentage: 10 // optional: Only available for Datafast (Equador). The tax percentage to be applied to this order.
                });

            }).catch((error) => { //error en la respuesta ajax
                console.log(error)
                swal(
                    ':( Lo sentimos ocurrio un error durante su petición',
                    ''+ error.status + ' ' + error.statusText + '',
                    'error'
                )
            });


        });

    }

    // Close Checkout on page navigation:
    window.addEventListener('popstate', function () {
        paymentezCheckout.close();
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