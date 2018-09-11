@extends('layouts.back.master')

@section('page_title','Crear Perfil')
@section('breadcrumbs')
    {!! Breadcrumbs::render('perfil-create') !!}
@stop


@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.css')}}">
@endpush

@section('content')


    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <h4 class="text-blue">Siga los pasos</h4>
            <p class="mb-30 font-14">Para crear su perfil</p>
        </div>
        <a href="#" data-toggle="modal" data-target="#perfil-search"
           class="bg-light-blue btn text-blue weight-500"><i class="ion-search"></i> Buscar
        </a>
        <div class="form-text text-info small">Si ya se ha incrito en ediciones anteriores puede buscar, sino siga los
            pasos siguientes
        </div>
        @include('personas.modals.perfil-search')
        <div class="wizard-content">
            {!! Form::open(['route' => 'personas.store', 'method' => 'post', 'autocomplete'=> 'off', 'class'=>'tab-wizard wizard-circle wizard','id'=>'form-wizard' ]) !!}
            {!! Form::hidden('persona_id',null,['id'=>'persona_id']) !!}

            <h5>Información Principal</h5>
            <section>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombres :</label>
                            {!! Form::text('nombres',null,['class'=>'form-control required','style'=>'text-transform: uppercase', 'value'=>'{{ old("nombres") }}','required','id'=>'nombres']) !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellidos :</label>
                            {!! Form::text('apellidos',null,['class'=>'form-control required','style'=>'text-transform: uppercase','value'=>'{{ old("apellidos") }}','required','id'=>'apellidos']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Nacimiento :</label>
                            {!! Form::text('fecha_nac',null,['class'=>'form-control date-picker required','onkeydown'=>'return false;','value'=>'{{ old("fecha_nac") }}','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Género</label>
                            {!! Form::select('gen', ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'],null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','value'=>'{{ old("gen") }}','required']) !!}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identificación: </label>
                            {!! Form::text('num_doc',null,['class'=>'form-control required','placeholder'=>'Cédula o Pasaporte','style'=>'text-transform: uppercase','value'=>'{{ old("num_doc") }}','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email :</label>
                            {!! Form::email('email',Auth::user()->email,['class'=>'form-control required','placeholder'=>'Email','style'=>'text-transform: lowercase','value'=>'{{ old("email") }}','required']) !!}
                            <small class="form-text text-muted">
                                Se utilizará por defecto para la facturación.
                            </small>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Step 2 -->
            <h5>Dirección y otras</h5>
            <section>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono :</label>
                            {!! Form::text('telefono',null,['class'=>'form-control','value'=>'{{ old("telefono") }}']) !!}
                        </div>
                        <div class="form-group">
                            <label>Discapacitado</label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mb-5 mr-20">
                                    <input type="radio" id="discapacidad-si" name="discapacitado"
                                           class="custom-control-input" value="si"
                                           @if(old('discapacitado')==="si") checked @endif>
                                    <label class="custom-control-label weight-400"
                                           for="discapacidad-si">Si</label>
                                </div>
                                <div class="custom-control custom-radio mb-5">
                                    <input type="radio" id="discapacidad-no" name="discapacitado"
                                           class="custom-control-input" value="no"
                                           @if(old('discapacitado')==="no") checked @endif>
                                    <label class="custom-control-label weight-400"
                                           for="discapacidad-no">No</label>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            PARA PERSONAS DISCAPACITADAS QUE APLICAN DESCUENTOS LAS
                            INCSCIPCIONES DEBEN REALIZARCE PRESENCIALMENTE EN CUALQUIER PUNTO DE INSCRIPCIÓN DE
                            FEDEGUAYAS
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dirección: </label>
                            <textarea class="form-control required" id="direccion" name="direccion"
                                      style="text-transform: uppercase" required>{{ old("direccion") }}</textarea>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Step 3 -->
            <h5>Privacidad</h5>
            <section>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Perfil privado?</label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mb-5 mr-20">
                                    <input type="radio" id="privacidad-si" name="privado"
                                           class="custom-control-input" value="si"
                                           @if(old('privado')==="si") checked @endif>
                                    <label class="custom-control-label weight-400"
                                           for="privacidad-si">Si</label>
                                </div>
                                <div class="custom-control custom-radio mb-5">
                                    <input type="radio" id="privacidad-no" name="privado"
                                           class="custom-control-input" value="no"
                                           @if(old('privado')==="no") checked @endif>
                                    <label class="custom-control-label weight-400"
                                           for="privacidad-no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 bg-white border-radius-4 box-shadow">
                        <h5 class="mb-30">Descripción</h5>
                        <dl class="row">
                            <dt class="col-sm-2">SI</dt>
                            <dd class="col-sm-10">
                                <p>Su perfil no aparecerá en las busquedas y no podrá ser asociado a una cuenta de un
                                    amigo.</p>
                                <p>Si su perfil se encuentra asociado a alguna cuenta de un amigo este se desvinculará
                                    de la misma</p>
                            </dd>

                            <dt class="col-sm-2">NO</dt>
                            <dd class="col-sm-10">
                                <p>Su perfil aparecerá en las busquedas de asociados.</p>
                                <p>Seleccione esta opción si desea ser inscrito a través de otra cuenta de usuario.</p>
                            </dd>

                        </dl>
                    </div>
                </div>
            </section>

            {!! Form::close() !!}
        </div>
    </div>

@endsection


@push('scripts')
<script src="{{asset('themes/back/src/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/jquery-validation/dist/localization/messages_es.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(document).ready(function (e) {
        $('.date-picker').datepicker({
            language: 'es',
            dateFormat: 'yyyy-mm-dd',
            position: 'right bottom',
            maxDate: new Date()
        });

    });


        $("#form-wizard").steps({
            headerTag: "h5",
            bodyTag: "section",
            transitionEffect: "fade",
            enableFinishButton: true,
            titleTemplate: '<span class="step">#index#</span> #title#',
            errorClass: "error",
            labels: {
                finish: "Enviar",
                previous: "Anterior",
                next: "Siguiente",
                loading: "Cargando..."
            },

//      Se dispara antes de que cambiar de paso y se puede usar para evitar el cambio de pasos al devolver falso. Muy útil para la validación de formularios.
            onStepChanging: function (event, currentIndex, newIndex) {
                let form = $(this);
                // ¡Siempre permite regresar a la acción previa incluso si el form actual no es válido!
                if (currentIndex > newIndex) {
                    return true;
                }
                //Prohibir la siguiente acción en el paso, "Advertencia" si el usuario es joven
//            if (newIndex === 3 && Number($("#age-2").val()) < 18)
//            {
//                return false;
//            }
                // Necesario en algunos casos si el usuario regresó (limpieza). Limpiar si el usuario retrocedió antes
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                //Deshabilite la validación en los campos que están deshabilitados u ocultos.
                form.validate().settings.ignore = ":disabled,:hidden";
                // Comience la validación; Prevenir el avance si es falso
                return form.valid();
            },
//        Se dispara después de que el paso ha cambiado.
            onStepChanged: function (event, currentIndex, priorIndex) {
                // Se usa para omitir el paso "Advertencia" si el usuario tiene edad suficiente.
//            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
//                $(this).steps("next");
//            }
                // Se usa para omitir el paso "Advertencia" si el usuario tiene la edad suficiente y quiere regresar al paso anterior.
//            if (currentIndex === 2 && priorIndex === 3) {
//                $(this).steps("previous");
//            }
            },
//        Se dispara antes de terminar y se puede usar para evitar la finalización al devolver falso. Muy útil para la validación de formularios.
            onFinishing: function (event, currentIndex) {
                let form = $(this);
                //Deshabilitar la validación en los campos que están deshabilitados.
                form.validate().settings.ignore = ":disabled";
                // Comience la validación; Evitar el envío de formularios si es falso
                return form.valid();
            },
//      Se dispara después de la finalización.
            onFinished: function (event, currentIndex) {
                let form = $(this);
                // Enviar entradas del formulario
                form.submit();
//            $('#success-modal').modal('show');
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            },
            rules: {
//            confirm: {
//                equalTo: "#password-2"
//            }
            }
        });


        //buscar perfil por la identificacion
        $("#search").on('click', function (event) {
            event.preventDefault();
            let identificacion = $("#search-doc").val();
            let route = "{{route('perfil.search')}}";
            let token = $("input[name=_token]").val();
            let persona_id = $("#persona_id");
            let first_name = $("#nombres");
            let last_name = $("#apellidos");

            $("#addNames").prop('checked', false);
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
                            $("#nombres-show").val('');
                            $("#apellidos-show").val('');
                            $("#persona_id_show").val('');
                            //limpio los input del formulario de crear el perfil
                            persona_id.val('');
                            first_name.val('');
                            last_name.val('');
                            //limpio el check
                        }
                        //se encontro persona que no tiene cuenta asociada
                        if (resp.result === 'found') {
                            $("#nombres-show").val(resp.persona.nombres);
                            $("#apellidos-show").val(resp.persona.apellidos);
                            $("#persona_id_show").val(resp.persona.id);
                        }

                    },
                    error: function (resp) {
//                    console.log(resp);
                    }
                });

                // Comprobar cuando cambia el checkbox
                $("#addNames").on('change', function () {
                    // si se activa el check de aceptar el perfil
                    if ($(this).is(':checked')) {
                        persona_id.val($("#persona_id_show").val());
                        first_name.val($("#nombres-show").val());
                        last_name.val($("#apellidos-show").val());
                        console.log('Seleccionado' + persona_id.val());
                    } else {
                        persona_id.val('');
                        first_name.val('');
                        last_name.val('');
                        console.log('Deselecciono' + persona_id.val());
                    }
                });
            }
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
