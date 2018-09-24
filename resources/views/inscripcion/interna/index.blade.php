@extends('layouts.back.master')

@section('page_title','Inscripciones')

@section('breadcrumbs')
    {!! Breadcrumbs::render('inscripciones') !!}
@stop

@push('styles')
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/jquery.dataTables.css')}}">
<link rel="stylesheet" type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet " type="text/css"
      href="{{asset('themes/back/src/plugins/datatables/media/css/responsive.dataTables.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Todas las inscripciones</h5>
                @can('add_inscripciones')
                    <p class="font-14">para inscribir debe buscar al <a class="btn btn-sm btn-outline-primary"
                                                                        href="{{route('personas.index')}}">cliente
                            <i class="fa fa-user"></i>
                        </a>
                    </p>
                @endcan
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="data-table stripe hover nowrap compact">
                    <thead>
                    <tr>
                        <th class="datatable-nosort">Acción</th>
                        <th>Nombres</th>
                        <th>Num. Identidad</th>
                        <th>Categoría</th>
                        <th>Circuito</th>
                        <th>Número</th>
                        <th>Kit</th>
                        {{--<th>Teléfono</th>--}}
                        {{--<th>Email</th>--}}
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_search"></th>
                        <th class="tfoot_select"></th>
                        <th class="tfoot_select"></th>
                        <th class="tfoot_search"></th>
                        <th></th>
                        {{--<th class="tfoot_search"></th>--}}
                        {{--<th class="tfoot_search"></th>--}}

                    </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {!! Form::open(['route'=>['admin.inscription.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete']) !!}
    {!! Form::close() !!}

    {!! Form::open(['route'=>['admin.inscripcion.setKit',':ID'],'method'=>'post','id'=>'form-statusKit']) !!}
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

//        $(".form_noEnter").keypress(function (e) {
//            if (e.which === 13) {
//                return false;
//            }
//        });

    });

    let  table;
    function cargarDatatables() {

        $('.tfoot_search').each(function () {
            let title = $(this).text();
            $(this).html('<input type="text" class="input-sm" placeholder="Buscar ' + title + '" />');
        });
        table = $(".data-table").DataTable({
            lengthMenu: [[5, 25, 50], [5, 25, 50]],
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
            ajax: '{{route('admin.inscription.getAll')}}',
            columns: [
                {data: 'actions', name: 'opciones', orderable: false, searchable: false},
                {data: 'nombres', name: 'nombres'},
                {data: 'persona.num_doc', name: 'persona.num_doc'},
                {data: 'producto.categoria.categoria', name: 'producto.categoria.categoria'},
                {data: 'producto.circuito.circuito', name: 'producto.circuito.circuito'},
                {data: 'numero', name: 'numero'},
                {data: 'kit', name: 'kit'}

//                {data: 'email', name: 'email', orderable: false},
//                {data: 'gen', name: 'gen'},
//                {data: 'fecha_nac', name: 'fecha_nac'},
//                {data: 'direccion', name: 'direccion'},
//                {data: 'telefono', name: 'telefono'}

            ],
            columnDefs: [
                {
                    targets: 6,
                    render: function (data, type, row, meta) {
                        if (type === 'display' && data=='1') {
                            data = '<i class="fa fa-thumbs-o-up text-primary" data-toggle="tooltip" data-placement="top" title="Entregado"></i>';
                        }else {
                            data = '<i class="fa fa-thumbs-o-down text-danger" data-toggle="tooltip" data-placement="top" title="Por Entregar"></i>';
                        }
                        return data;
                    }
                }
//                {
//                    targets: [8,9,10,11],
//                    className: "text-right",
//                    render:  $.fn.dataTable.render.number(' ', '.', 2, '$ ')
//                }
//                { //Dar color a los datos de todas las columnas
//                    targets: '_all',
//                    render: function (data, type, row) {
//                        var color = 'black';
//                        return '<strong style="color:' + color + '">' + data + '</strong>';
//                    }
//                }
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//                console.log(aData);
            },
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();//para que funcionen los tooltips en cada fila
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
                        let select = $('<select ><option value="">Seleccione ...</option></select>')
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
//                        column.data().unique().sort().each(function (d, j) {
//                            select.append('<option value="' + d + '">' + d + '</option>')
//                        });

                        // Capturar los datos del JSON para llenar el select con las opciones
                        let extraData = (function (i) {
                            switch (i) {
                                case 3:
                                    return json.allCategorias;
                                case 4:
                                    return json.allCircuitos;
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
