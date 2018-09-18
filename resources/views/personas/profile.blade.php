@extends('layouts.back.master')

@section('page_title','Perfil')
@section('breadcrumbs')
    {!! Breadcrumbs::render('perfil') !!}
@stop

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/cropperjs/dist/cropper.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/dropzone/src/dropzone.css')}}">
<style>
    .progress {
        display: none;
        margin-bottom: 1rem;
    }

    .img-container img {
        max-width: 100%;
    }
</style>
@endpush

@section('content')

    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 bg-white border-radius-4 box-shadow">
                <div class="profile-photo">
                    <a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar"><i
                                class="fa fa-pencil"></i></a>
                    @if ((Auth::user()->avatar)!=NULL)
                        <img src="{{ asset('dist/img/users/perfil/'.Auth::user()->avatar)}}" alt="Foto de Usuario"
                             class="avatar-photo" id="avatar" style="max-height: 100%">
                    @else
                        <img src="{{asset('images/default-user.jpg')}}" alt="" class="avatar-photo">
                    @endif

                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                             aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                        </div>
                    </div>

                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body pd-5">
                                    <div class="img-container">
                                        @if ((Auth::user()->avatar)!=NULL)
                                            <img id="user-avatar-crop"
                                                 src="{{asset('dist/img/users/perfil/'.Auth::user()->avatar)}}"
                                                 alt="Foto de Usuario">
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" value="Actualizar" class="btn btn-primary"
                                           id="user-avatar-update">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="text-center">{{Auth::user()->getFullName()}}</h5>
                <p class="text-center text-muted" style="overflow-wrap:break-word;">
                    {{ucwords(Auth::user()->getRoleNames())}}
                </p>
                <h6 class="text-center">Cuenta</h6>
                <p class="text-center text-muted" style="overflow-wrap:break-word;">
                    {{Auth::user()->email}}
                </p>

                <div class="profile-info">
                    <h5 class="mb-20 weight-500">Información de Contacto</h5>
                    <ul>
                        <li>
                            <span>Email:</span>
                            {{$persona->email}}
                        </li>
                        <li>
                            <span>Phone Number:</span>
                            {{$persona->telefono}}
                        </li>
                        <li>
                            <span>Dirección:</span>
                            {{$persona->direccion}}<br>

                        </li>
                    </ul>

                </div>


            </div>
        </div>
        <div class="col-xl-9 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="bg-white border-radius-4 box-shadow height-100-p">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            {{--<li class="nav-item">--}}
                                {{--<a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">Cronología</a>--}}
                            {{--</li>--}}
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#setting" role="tab">Configuración</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#asociados" role="tab">Asociados</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
{{--                            @include('personas.partials.cronologia')--}}
                            <!-- Timeline Tab End -->

                            <!-- Setting Tab start -->
                        @include('personas.partials.configuracion')
                        <!-- Setting Tab End -->


                            <!-- Asociados Tab start -->
                            @include('personas.partials.asociados')
                            <!-- Tasks Tab End -->


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::open(['route'=>['perfil-asociado.destroy',':ID'],'method'=>'DELETE','id'=>'form-asociado-delete']) !!}
    {!! Form::close() !!}

@endsection


@push('scripts')
<script src="{{asset('themes/back/src/plugins/air-datepicker/dist/js/i18n/datepicker.es.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/cropperjs/dist/cropper.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/dropzone/src/dropzone.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>
    //cropper
    window.addEventListener('DOMContentLoaded', function () {
        let avatar = document.getElementById('avatar');
        let image = document.getElementById('user-avatar-crop');
        let $progress = $('.progress');
        let $progressBar = $('.progress-bar');
        let $modal = $('#modal');
        let cropper;
        let cropBoxData;
        let canvasData;


        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                autoCropArea: 0.5,
                dragMode: 'move',
                aspectRatio: 3 / 3,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: false,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
//                vieMode:3,
                ready: function () {
                    cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
//                    console.log(this.cropper === cropper);
//                    cropper.crop();
                }
            });
        }).on('hidden.bs.modal', function () {
            cropBoxData = cropper.getCropBoxData();
            canvasData = cropper.getCanvasData();
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('user-avatar-update').addEventListener('click', function () {
            let initialAvatarURL;
            let canvas;

            $modal.modal('hide');

            if (cropper) {
                canvas = cropper.getCroppedCanvas({
                    width: 160,
                    height: 160
                });

                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL();
                $progress.show();
                canvas.toBlob(function (blob) {
                    let formData = new FormData();
                    formData.append('avatar', blob);
                    let token = $("input[name=_token]").val();
                    $.ajax({
                        url: "{{route('user.avatarCrop.upload')}}",
                        headers: {'X-CSRF-TOKEN': token},
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,

                        xhr: function () {
                            let xhr = new XMLHttpRequest();
                            xhr.upload.onprogress = function (e) {
                                let percent = '0';
                                let percentage = '0%';
                                if (e.lengthComputable) {
                                    percent = Math.round((e.loaded / e.total) * 100);
                                    percentage = percent + '%';
                                    $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                                }
                            };
                            return xhr;
                        },

                        success: function (resp) {
                            swal(
                                {
                                    type: 'success',
                                    title: '',
                                    text: 'Actualización correcta'
                                }
                            );
                        },
                        error: function () {
                            avatar.src = initialAvatarURL;
                            swal(
                                {
                                    type: 'error',
                                    title: '',
                                    text: 'Error no se pudo actualizar su avatar'
                                }
                            );
                        },
                        complete: function () {
                            $progress.hide();
                        }
                    });
                });
            }
        });
    });
    //fin cropperjs

    //dropzone
    //    Dropzone.autoDiscover = false;
    Dropzone.options.myDropzone = {
//        paramName: "avatar",
        addRemoveLinks: true,
        autoProcessQueue: false, //no subir rchivo automaticamente
        maxFilezise: 1, //1MB
        dictDefaultMessage: 'Arrastra la imagen aquí para subirla',
        dictRemoveFile: 'Quitar imagen',
        dictFileTooBig: 'Imagen demasiado grande, no debe superar 1MB',
        acceptedFiles: '.jpg',
//        removedfile: function(file) {
//            var name = file.name;
//            var _ref;
//            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
//        },
        init: function () {
            let submitBtn = document.querySelector("#submit-avatar");
            myDropzone = this;

            submitBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue();
            });
            this.on("addedfile", function (file) {
                alert("Archivo listo para ser subido");
            });

            this.on("complete", function (file) {
                myDropzone.removeFile(file);
            });

            this.on("success",
                myDropzone.processQueue.bind(myDropzone)
            );
        }
    };
    //fin dropzone

    $(document).ready(function () {


        //reset modal on before show to user
        $('#asociado-search').on('show.bs.modal', function (e) {
            $(this).find('form').trigger('reset');
            $("#addNames").prop('disabled',true);
            $("#aceptar-vinculado").prop('disabled',true);
        });

        $("#cerrar-modal").on('click',function () {
            $("#addNames").prop('disabled',true);
            $("#aceptar-vinculado").prop('disabled',true);
            swal(
                {
                    type: 'info',
                    title: 'Oops...',
                    text: 'Cerro la ventana sin aceptar datos!'
                }
            );

        });

        //buscar perfiles que no tengan cuentas de usuario para asociarlos a la cuenta logueada
        $("#search").on('click', function (event) { //boton search del modal
            event.preventDefault();
            let identificacion = $("#search-doc").val();
            let route = "{{route('perfil-asociado.search')}}";
            let token = $("input[name=_token]").val();
            let addNames=$("#addNames"); //checkbox
            let aceptarVinculado = $("#aceptar-vinculado"); //boton de aceptar modal
            let nombreShow=$("#nombres-show");
            let apellidoShow=$("#apellidos-show");
            let personaIdShow=$("#persona_id_show");


            if (identificacion === "") {
                swal(
                    {
                        type: 'info',
                        title: 'Oops...',
                        text: 'Debe ingresar datos en el campo de busqueda!'
                    }
                );
            } else {
                $.ajax({
                    url: route,
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': token},
                    contentType: 'application/x-www-form-urlencoded',
                    data: {
                        identificacion: identificacion
                    },
                    success: function (resp) {

                        //No se encontro a la persona
                        if (resp.result === 'not-found') {
                            swal(
                                {
                                    type: 'error',
                                    title: resp.message,
                                    text: 'Si el resultado no es el esperado puede refinar su busquedad modificando el campo Identificación!.'
                                }
                            );
                            //limpio campos del formulario del modal
                            nombreShow.val('');
                            apellidoShow.val('');
                            personaIdShow.val('');
                            addNames.prop('disabled', true);
                            aceptarVinculado.prop('disabled', true);
                        }
                        //se encontro persona
                        if (resp.result === 'found') {
                            nombreShow.val(resp.persona.nombres);
                            apellidoShow.val(resp.persona.apellidos);
                            personaIdShow.val(resp.persona.id);
                            addNames.prop('disabled', false);
                        }

                    },
                    error: function (resp) {
                        addNames.prop('disabled',true);
                        aceptarVinculado.prop('disabled',true);
//                    console.log(resp);
                    }
                });

                // Comprobar cuando cambia el checkbox
                $("#addNames").on('change', function () {
                    // si se activa el check de aceptar el perfil
                    if ($(this).is(':checked')) {
                        $("#info-asociado").prop('hidden', false);
                        aceptarVinculado.prop('disabled', false);

                    } else {
                        $("#info-asociado").prop('hidden', true);
                        personaIdShow.val('');
                        aceptarVinculado.prop('disabled', true);
                    }
                });
            }
        });
    });

    $(document).ready(function () {
        //no enviar formularios al dar enter
        $(".form_noEnter").keypress(function (e) {
            if (e.which === 13) {
                return false;
            }
        });

        //Habilitar Desabilitar inputs de formulario personal
        let update_personal = function () {
            if ($("#editar-personal").is(":checked")) {
                $("#personal-form").find('input, textarea, button, select').prop('disabled', false);
            }
            else {
                $("#personal-form").find('input, textarea, button, select').prop('disabled', 'disabled');
            }
            $("#editar-personal").prop('disabled', false);
        };
        $(update_personal);
        $("#editar-personal").change(update_personal);

        //Habilitar Desabilitar inputs de formulario de la cuenta
        let update_cuenta = function () {
            if ($("#editar-cuenta").is(":checked")) {
                $("#cuenta-form").find('input, button').prop('disabled', false);
            }
            else {
                $("#cuenta-form").find('input, button').prop('disabled', 'disabled');
            }
            $("#editar-cuenta").prop('disabled', false);
        };
        $(update_cuenta);
        $("#editar-cuenta").change(update_cuenta);


                {{--Alertas con Toastr--}}
                @if(Session::has('message_toastr'))
        let type = "{{ Session::get('alert-type') }}";
        let text_toastr = "{{ Session::get('message_toastr') }}";
        showAlert(type, text_toastr);
        @endif
        {{-- FIN Alertas con Toastr--}}

    });

    //eliminar asociado
    $(document).on('click', '.delete-asociado', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let token = $("input[name=_token]").val();
        let form = $("#form-asociado-delete");
        let url = form.attr('action').replace(':ID', id);
        let data = form.serialize();
        swal({
            title: 'Confirme la acción',
            text: "Se eliminará el asociado a su cuenta!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminarlo! <i class="fa fa-thumbs-up"></i>',
            cancelButtonText: 'No, cancelar! <i class="fa fa-thumbs-o-down"></i>',
            showCloseButton: true,
            confirmButtonClass: 'btn btn-outline-primary m5',
            cancelButtonClass: 'btn btn-outline-secondary m-5',
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: url,
                        data: data,
                        headers: {'X-CSRF-TOKEN': token},
                        type: "POST",
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
            //confirmo la eliminacion
            if (result.value) {
                swal({
                    title: ':)',
                    text: 'Se eliminó el asociado',
                    type: 'success',
                    allowOutsideClick: false
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


</script>
@endpush

