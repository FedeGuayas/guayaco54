@extends('layouts.back.master')

@section('page_title','Editar Asociado')
@section('breadcrumbs')
    {!! Breadcrumbs::render('perfil-as-edit') !!}
@stop


@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.css')}}">
@endpush

@section('content')


    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <h4 class="text-blue">Siga los pasos</h4>
            <p class="mb-30 font-14">Para editar el perfil de un asociado a su cuenta.</p>
        </div>

        <div class="wizard-content">
            {!! Form::model($persona, ['route' => ['perfil-asociado.update', $persona->id],'method'=>'PUT','class'=>'tab-wizard wizard-circle wizard','id'=>'form-wizard','autocomplete'=> 'off']) !!}

            <h5>Información Principal</h5>
            <section>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombres: *</label>
                            {!! Form::text('nombres',null,['class'=>'form-control required','style'=>'text-transform: uppercase','required','id'=>'nombres']) !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellidos: *</label>
                            {!! Form::text('apellidos',null,['class'=>'form-control required','style'=>'text-transform: uppercase','required','id'=>'apellidos']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Nacimiento: *</label>
                            {!! Form::text('fecha_nac',null,['class'=>'form-control date-picker required','onkeydown'=>'return false;','required','data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-position'=>'right bottom']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Género: *</label>
                            {!! Form::select('gen', ['MASCULINO' => 'Masculino', 'FEMENINO' => 'Femenino'],$persona->gen, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','required']) !!}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identificación: *</label>
                            {!! Form::text('num_doc',null,['class'=>'form-control required','placeholder'=>'Cédula o Pasaporte','style'=>'text-transform: uppercase','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email :</label>
                            {!! Form::email('email',null,['class'=>'form-control required','placeholder'=>'Email','style'=>'text-transform: lowercase','required']) !!}
                            <small class="form-text text-muted">
                                Se utilizará por defecto para la facturación.
                            </small>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Step 2 -->
            <h5>Terminar y Enviar</h5>
            <section>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono:</label>
                            {!! Form::text('telefono',null,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Discapacitado: *</label>
                            <div class="d-flex">
                                <div class="custom-control custom-radio mb-5 mr-20">
                                    <input type="radio" id="discapacidad-si" name="discapacitado"
                                           class="custom-control-input" value="si"
                                           @if( $persona->discapacitado==\App\Persona::DISCAPACITADO) checked @endif>
                                    <label class="custom-control-label weight-400"
                                           for="discapacidad-si">Si</label>
                                </div>
                                <div class="custom-control custom-radio mb-5">
                                    <input type="radio" id="discapacidad-no" name="discapacitado"
                                           class="custom-control-input" value="no"
                                           @if( $persona->discapacitado==\App\Persona::NO_DISCAPACITADO) checked @endif>
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
                            <label>Dirección: *</label>
                            <textarea class="form-control required" id="direccion" name="direccion"
                                      style="text-transform: uppercase" required>{{ $persona->direccion}}</textarea>
                        </div>
                    </div>
                </div>
            </section>

            <small class="form-text text-danger">
                * Campos Obligatorios
            </small>

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
            }
        }).validate({
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
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
