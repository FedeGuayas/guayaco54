@extends('layouts.back.master')

@section('page_title','Usuarios')

@section('breadcrumbs')
    {!! Breadcrumbs::render('user') !!}
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
                <h5 class="text-blue">Todas los Usuarios</h5>
                @can('add_users')
                    <p class="font-14">para crear uno nuevo <a class="btn btn-sm btn-outline-primary"
                                                               href="{{route('users.create')}}">
                            <i class="fa fa-user-plus"></i>
                        </a>
                    </p>
                @endcan
            </div>
        </div>

        <div class="row">
            <table class="data-table stripe hover nowrap compact">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th ></th>
                    <th ></th>
                    <th ></th>
                    <th class="tfoot_select" ></th>
                    <th ></th>
                </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {!! Form::open(['route'=>['users.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete']) !!}
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

    function cargarDatatables() {

        $('.tfoot_search').each(function () {
            let title = $(this).text();
            $(this).html('<input type="text" class="input-sm" placeholder="Buscar ' + title + '" />');
        });
        let table = $(".data-table").DataTable({
            lengthMenu: [[3, 5, 10], [3, 5, 10]],
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            processing: true,
            select: true,
            serverSide: true,
            order: [[0, 'desc']],
            "language": {
                "url": '/guayaco-runner/plugins/DataTables/i18n/Spanish_original.lang'
            },
            ajax: '{{route('getAllUsers')}}',
            columns: [
                {data: 'id', name: 'id'},//Id
                {data: 'nombres', orderable: false},//nombres
                {data: 'email', name: 'email', orderable: false, searchable: false},//email
                {data: 'roles', name: 'roles', orderable: false, searchable: false},//Area
                {data: 'actions', name: 'opciones', orderable: false, searchable: false}
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                console.log(aData);
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

                        // Cacturar los datos del JSON para llenar el select con las opciones
                        let extraData = (function (i) {
                            switch (i) {
                                case 3:
                                    return json.allRoles;

                            }
                        })(column.index());

                        // Draw select options
                        extraData.forEach(function (d) {
                            if (column.search() === d) {
                                select.append('<option value="' + d + '" selected="selected">' + d + '</option>')
                            } else {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            }
                        });
//                            column.data().unique().sort().each( function ( d, j ) {
//                                select.append( '<option value="'+d+'">'+d+'</option>' )
//                            });
                    }
                });
            }

        });

    }



    //eliminar usuario
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-delete");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se eliminará el permiso!",
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
                    text: 'Permiso eliminado correctamente',
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
