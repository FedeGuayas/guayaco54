@extends('layouts.back.master')

@section('page_title','Roles')

@section('breadcrumbs')
    {!! Breadcrumbs::render('role') !!}
@stop

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Todos los Roles</h5>
                @can('add_roles')
                    <p class="font-14">para crear uno nuevo
                        <a class="btn btn-sm btn-outline-primary"
                           href="{{route('roles.create')}}">
                            Click <i class="fa fa-plus"></i>
                        </a>

                    </p>
                @endcan
            </div>
        </div>

        @forelse ($roles as $role)
            {!! Form::model($role, ['method' => 'PUT', 'route' => ['roles.update',  $role->id ], 'class' => 'm-b']) !!}
            <div class="form-group">
                @if($role->name === 'admin')
                    @include('shared._permissions', [
                                  'title' => 'Permisos para: '.'"'.$role->name.'"',
                                  'options' => ['disabled'] ])
                @else
                    @include('shared._permissions', [
                                  'title' => 'Permisos para: '.'"'.$role->name.'"',
                                  'model' => $role ]
                                  )
                    {{--@can('edit_roles')--}}
                        {{--Submit--}}
                    {{--@endcan--}}
                @endif
            </div>
            {!! Form::close() !!}

        @empty
            <p>No Roles defined, please run <code>php artisan db:seed</code> to seed some dummy data.</p>
        @endforelse

    </div>

    {!! Form::open(['route'=>['roles.destroy',':ID'],'method'=>'DELETE','id'=>'form-delete-role']) !!}
    {!! Form::close() !!}

@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>


    //eliminar rol
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let rname = $(this).attr('data-rname');
        let token = $("input[name=_token]").val();
        let form = $("#form-delete-role");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se eliminará el rol: "+rname,
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
                    text: 'Rol eliminado correctamente',
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