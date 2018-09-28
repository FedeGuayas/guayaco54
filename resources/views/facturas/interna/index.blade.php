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

        <div class="row">
            <div class="table-responsive">
                <div class="dataTables_wrapper container-fluid dt-bootstrap4">
                    <div class="dt-buttons btn-group pull-right">
                        <form class="" action="" autocomplete="off">
                            <div class="form-group row">
                                <div class="col">
                                    <input type="text" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" class="form-control date-picker" placeholder="YYYY-MM-DD" name="desde" id="desde"/>
                                    <small class="form-text text-muted">Desde (año-mes-dia)</small>
                                </div>
                                <div class="col">
                                    <input type="text" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" class="form-control date-picker" placeholder="YYYY-MM-DD" name="hasta" id="hasta"/>
                                    <small class="form-text text-muted">Hasta (año-mes-dia)</small>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-outline-primary" id="facturacion_masiva"><i class="fa fa-file-excel-o"></i> Facturación Masiva</button>
                                </div>

                            </div>
                        </form>
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
<!-- buttons for Export datatable -->
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/dataTables.buttons.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/buttons.bootstrap4.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/buttons.print.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/buttons.html5.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/buttons.flash.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/pdfmake.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/datatables/media/js/button/vfs_fonts.js')}}"></script>

<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            console.log(data)
//            var min = parseInt( $('#min').val(), 10 );
//            var max = parseInt( $('#max').val(), 10 );
//            var age = parseFloat( data[3] ) || 0; // use data for the age column
//
//            if ( ( isNaN( min ) && isNaN( max ) ) ||
//                ( isNaN( min ) && age <= max ) ||
//                ( min <= age   && isNaN( max ) ) ||
//                ( min <= age   && age <= max ) )
//            {
//                return true;
//            }
//            return false;
        }
    );


    //        $.fn.dataTable.ext.search.push(
    //            function( settings, data, dataIndex ) {
    ////            var min = parseInt( $('#min').val(), 10 );
    ////            var max = parseInt( $('#max').val(), 10 );
    ////            var age = parseFloat( data[3] ) || 0; // use data for the age column
    //                var desde = $('#desde').val();
    //                var hasta = document.getElementById('hasta').value;
    //                var fecha =  data[7]; // use data for the age column
    //                console.log(desde);
    //                if ( desde === "" && hasta === "" )
    //                {
    //                    return true;
    //                }
    //                else if ( desde <= fecha && hasta === "")
    //                {
    //                    return true;
    //                }
    //                else if ( hasta >= fecha && desde === "")
    //                {
    //                    return true;
    //                }
    //                else if (desde <= fecha && hasta >= fecha)
    //                {
    //                    return true;
    //                }
    //                return false;
    //                var startDate = new Date(aData[7]);
    //                if (dateStart == null && dateEnd == null) { return true; }
    //                if (dateStart == null && startDate <= dateEnd) { return true;}
    //                if(dateEnd == null && startDate >= dateStart) {return true;}
    //                if (startDate <= dateEnd && startDate >= dateStart) { return true; }
    //                return false;
    //
    //            if ( ( isNaN( desde ) && isNaN( hasta ) ) ||
    //                ( isNaN( desde ) && age <= hasta ) ||
    //                ( desde <= age   && isNaN( hasta ) ) ||
    //                ( desde <= age   && age <= max ) )
    //            {
    //                return true;
    //            }
    //            return false;
    //            }
    //        );

    $(document).ready(function () {


        cargarDatatables();

//        $(".form_noEnter").keypress(function (e) {
//            if (e.which === 13) {
//                return false;
//            }
//        });
        

        $('#desde').datepicker({
            language: 'es',
            dateFormat: 'yyyy-mm-dd',
            position: 'right bottom',
//            maxDate: new Date()
            maxDate:0,
            onSelect: function onSelect(fd, date) {
//                table.columns(7).search(fd).draw();
               let desde = fd;
                table.column($(this).parent().index() + ':visible').search(desde).draw();

//                let val = $.fn.dataTable.util.escapeRegex(
//                    $(this).val()
//                );
//                column
//                    .search(val ? '^' + val + '$' : '', true, false)
//                    .draw();

//                table.draw();

            }
        }).change(function() {
              // getting search input value
            let desde= $(this).val();
            table.draw();
        });

        $('#hasta').datepicker({
            language: 'es',
            dateFormat: 'yyyy-mm-dd',
            position: 'right bottom',
//            maxDate: new Date()
            maxDate:0,
            onSelect: function onSelect(fd, date) {
//                table.columns(7).search(fd).draw();
                let hasta = fd;
                table.draw();
            }
        }).change(function() {
            // getting search input value
            let hasta = $(this).val();
            table.draw();
        });


    });

    $(document).on('click', '#facturacion_masiva', function (event) {
        event.preventDefault();
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
            ajax: '{{route('admin.getAll.inside')}}',
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
                    className: "text-right text-blue",
                    render: $.fn.dataTable.render.number(' ', '.', 2, '$ ')
                }
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//                console.log(aData);
//                var iFini = document.getElementById('desde').value;
//                var iFfin = document.getElementById('hasta').value;
//                var iStartDateCol = 7;
//                var iEndDateCol = 7;


//                iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
//                iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);


//
//                var datofini=aData[iStartDateCol].substring(6,10) + aData[iStartDateCol].substring(3,5)+ aData[iStartDateCol].substring(0,2);
//                var datoffin=aData[iEndDateCol].substring(6,10) + aData[iEndDateCol].substring(3,5)+ aData[iEndDateCol].substring(0,2);
//console.log(datofini+' / '+datoffin);
//                if ( iFini === "" && iFfin === "" )
//                {
//                    return true;
//                }
//                else if ( iFini <= datofini && iFfin === "")
//                {
//                    return true;
//                }
//                else if ( iFfin >= datoffin && iFini === "")
//                {
//                    return true;
//                }
//                else if (iFini <= datofini && iFfin >= datoffin)
//                {
//                    return true;
//                }
//                return false;
//                var startDate = new Date(aData[7]);
//                if (dateStart == null && dateEnd == null) { return true; }
//                if (dateStart == null && startDate <= dateEnd) { return true;}
//                if(dateEnd == null && startDate >= dateStart) {return true;}
//                if (startDate <= dateEnd && startDate >= dateStart) { return true; }
//                return false;
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
////                        // Draw select options
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
