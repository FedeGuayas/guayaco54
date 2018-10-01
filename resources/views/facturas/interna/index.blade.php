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
                <h5 class="text-blue">Todas los Comprobantes</h5>

            </div>
        </div>

        @include('facturas.interna.before-modal')

        <div class="row">
            <div class="table-responsive">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                    <div class="dt-buttons btn-group pull-right">
                        {!! Form::open() !!}
                        <div class="form-group row">
                            <div class="col">
                                <input type="text" class="form-control date-picker" name="desde" id="desde" readonly/>
                                <small class="form-text text-muted">Desde</small>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control date-picker" name="hasta" id="hasta" readonly/>
                                <small class="form-text text-muted">Hasta</small>
                            </div>
                            <div class="col">
                                <a href="#facturacionMasiva" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-outline-primary"
                                   id="facturacion_masiva"><i class="fa fa-file-excel-o"></i> Facturación Masiva</a>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>


                <table class="data-table stripe hover nowrap compact" style="display:none;">
                    <thead>
                    <tr>
                        <th class="datatable-nosort">Acción</th>
                        <th width="5">Comprobante</th>
                        <th>Nombres Fac.</th>
                        <th>Num. Identidad</th>
                        <th>Email</th>
                        <th>F. Pago</th>
                        <th>Valor</th>
                        <th>Fecha</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_select"></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! Form::open(['route'=>['facturas.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{asset('themes/back/src/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/dataTables.responsive.js')}}"></script>

<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(document).ready(function () {

        cargarDatatables();

        $(".form_noEnter").keypress(function (e) {
            if (e.which === 13) {
                return false;
            }
        });

        desde = '';
        hasta = '';
        $('.date-picker').datepicker({
            language: 'es',
            dateFormat: 'yyyy-mm-dd',
            position: 'right bottom',
            clearButton: true,
//            todayButton:true,
            todayButton: new Date(),
            maxDate: new Date(),
            onSelect: function onSelect(fd, date, event) {
//                table.columns(7).search(fd).draw();
                if (event.el.id == 'desde') {
                    desde = fd;
                } else if (event.el.id == 'hasta') {
                    hasta = fd
                }
                table.draw();
                //table.column(7).search(desde).draw();

            }
        });

    });

    $(document).on('click', '#facturacion_masiva', function (event) {
        event.preventDefault();
        console.log('Desde: ' + desde + ' Hasta: ' + hasta)
    });

    let table;
    function cargarDatatables() {

        $('.tfoot_search').each(function () {
            let title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Buscar ' + title + '" />');
        });
        table = $(".data-table").DataTable({
            lengthMenu: [[5, 25, 50, 100, 500, -1], [5, 25, 50, 100, 500, 'Todos']],
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            processing: true,
            select: true,
            serverSide: true,
//            order: [[0, 'desc']],
            "language": {
                "url": '/guayaco-runner/plugins/DataTables/i18n/Spanish_original.lang'
            },
            ajax: {
                url: "{{route('admin.getAll.inside')}}",
                data: function (d) {
                    d.desde = desde;
                    d.hasta = hasta;
                }
            },
            columns: [
                {data: 'actions', name: 'opciones', orderable: false, searchable: false},
                {data: 'numero', name: 'numero'},
                {data: 'nombre', name: 'nombre'},
                {data: 'identificacion', name: 'identificacion'},
                {data: 'email', name: 'email', orderable: false},
                {data: 'mpago.nombre', name: 'mpago.nombre', orderable: false},
                {data: 'total', name: 'total'},
                {data: 'created_at', name: 'created_at'}
            ],
            columnDefs: [
                {
                    targets: [6],
                    className: "text-center text-blue",
                    render: $.fn.dataTable.render.number(' ', '.', 2, '$ ')
                }
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//                console.log(aData);
            },
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();//para que funcionen los tooltips en cada fila

            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;
                // formatear los datos para sumar
                var intVal = function (i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };
                // Total en la pagina actual
                pageTotal = api.column(6, {page: 'current'}).data().reduce(function (a, b) {
                    return (intVal(a) + intVal(b)).toFixed(2);
                }, 0);
                // actualzar total en el pie de tabla
                $(api.column(6).footer()).html('<p> <b>$' + pageTotal + '</b>');
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
//                         Capturar los datos del JSON para llenar el select con las opciones
                        let extraData = (function (i) {
                            switch (i) {
                                case 5:
                                    return json.allMPago;
                            }
                        })(column.index());
                        // Draw select options
                        extraData.forEach(function (d, j) {
                            if (column.search() === d) {
                                select.append('<option value="' + d + '" selected="selected">' + d + '</option>')
                            } else {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
                    }


                });
            }

        });

    }




    //enviar info al modal antes de cargarlo
    $('#facturacionMasiva').on('show.bs.modal', function (event) {
        let modal = $(this);
        let diaDesde = desde;
        let diaHasta = hasta;
        modal.find('.modal-content #fecha_desde').val(diaDesde);
        modal.find('.modal-content #fecha_hasta').val(diaHasta);
    });
    //enviar el form del modal
    $(document).on('click', '#send_facturacion', function (e) {
        e.preventDefault();
        let form = $("#facturacion-form");
        form.submit();
    });



    //eliminar usuario
    $(document).on('click', '.status_kit', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-statusKit");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Afectará el estado del KIT!",
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
                            console.log(response)
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
//                console.log(response)
                swal({
                    title: ':)',
                    text: 'Acción satisfactoria',
                    type: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((resp) => {

                    if (resp.value) { //recargar al dar en ok
                        table.ajax.reload();
//                        window.setTimeout(function () {
//                            location.reload()
//                        }, 1);
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
            console.log(error)
            swal(
                ':( Lo sentimos ocurrio un error durante su petición',
                '' + error.status + ' ' + error.statusText + '',
                'error'
            )
        });
    });


    //habilitar /deshabilitar circuito
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
    var type = "{{ Session::get('alert-type') }}";
    var text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
            @endif
            {{-- FIN Alertas con Toastr--}}
            {{--errorres de validacion--}}
            @if ($errors->any())
    var errors = [];
    var error = '';
    @foreach ($errors->all() as $error)
errors.push("{{$error}}");
    @endforeach
    if (errors) {
        $.each(errors, function (i) {
            error += errors[i] + '<br>';
        });
    }
    showError(error);
    @endif

</script>
@endpush
