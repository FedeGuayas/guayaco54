@extends('layouts.back.master')

@section('page_title','Inscribirse')

@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop


@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/my_paymentez.css')}}">
<link href="https://cdn.paymentez.com/js/ccapi/stg/paymentez.min.css" rel="stylesheet" type="text/css"/>

{{--<style>--}}
{{--.error {--}}
{{--color: #a94442;--}}
{{--}--}}
{{--</style>--}}
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <h4 class="text-info">Siga los pasos</h4>
            <p class="mb-30 font-14">Para inscribirse en la carrera</p>
        </div>

        <div class="form-text text-info small">Si desea inscribir a un amigo
            <a href="{{route('getProfile')}}#asociados.tab('show')" class="weight-600"><i class="ion-man"></i>, click
                aqui
            </a> para cargar al Asociado
        </div>

        <div class="wizard-content">

            {!! Form::open(['method' => 'post', 'autocomplete'=> 'off', 'class'=>'tab-wizard wizard-circle wizard','id'=>'form-wizard' ]) !!}
            {!! Form::hidden('asociado_id',null,['id'=>'asociado_id']) !!}

            {{--Paso 1--}}
            <h5>Carrera</h5>
            <section>

                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Categorías: *</label>
                            {!! Form::select('categoria_id', $categorias,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','id'=>'categoria_id','value'=>'{{ old("categoria_id") }}','data-container'=>'.main-container','placeholder'=>'Seleccione ...']) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Circuitos: *</label>
                            {!! Form::select('circuito_id',[] ,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','placeholder'=>'Seleccione ...','id'=>'circuito_id','value'=>'{{ old("circuito_id") }}','data-container'=>'.main-container']) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Talla camiseta: </label>
                            <span class="badge badge-pill badge-primary pull-right" id="stock-talla"
                                  data-toggle="tooltip" data-placement="top" title="Stock">0</span>
                            {!! Form::select('talla', $tallas,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','id'=>'talla','value'=>'{{ old("talla") }}','placeholder'=>'Seleccione ...', 'data-live-search'=>'true','data-container'=>'.main-container','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="costo" class="weight-600">Costo: </label>
                            <div class="input-group">
                                <span class="input-group-prepend">
                                        <button class="btn btn-outline-secondary disabled">$</button>
                                </span>
                                {!! Form::text('costo',null, ['class'=>'form-control','id'=>'costo','placeholder'=>'0.00','readonly']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <small class="form-text text-danger"> * Campos obligatorios</small>
            </section>

            {{--Paso 2--}}
            <h5>Facturación</h5>
            <section>
                <h5 class="text-primary">Información para la Facturación</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombres :</label>
                            {!! Form::text('nombres',$perfil->nombres,['class'=>'form-control required','style'=>'text-transform: uppercase', 'value'=>'{{ old("nombres") }}','required','id'=>'nombres']) !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellidos :</label>
                            {!! Form::text('apellidos',$perfil->apellidos,['class'=>'form-control required','style'=>'text-transform: uppercase','value'=>'{{ old("apellidos") }}','required','id'=>'apellidos']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identificación: </label>
                            {!! Form::text('num_doc',$perfil->num_doc,['class'=>'form-control required','style'=>'text-transform: uppercase','value'=>'{{ old("num_doc") }}','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email :</label>
                            {!! Form::email('email',$perfil->email ? $perfil->email : Auth::user()->email,['class'=>'form-control required','placeholder'=>'Email','style'=>'text-transform: lowercase','value'=>'{{ old("email") }}','required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono :</label>
                            {!! Form::text('telefono',$perfil->telefono ? $perfil->telefono : '',['class'=>'form-control','value'=>'{{ old("telefono") }}']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dirección: </label>
                            {!! Form::text('direccion',$perfil->direccion ? $perfil->direccion : '',['class'=>'form-control','value'=>'{{ old("direccion") }}']) !!}
                        </div>
                    </div>
                </div>

            </section>


            {{--Paso 3--}}
            <h5>Pago y Términos</h5>
            <section>
                <div class="row">
                    <div class="col-md-4">
                        <label class="weight-600">Términos y Condiciones</label>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="terminos">
                            <label class="custom-control-label" for="terminos">Acepto los
                                <a href="#terminos-modal" data-toggle="modal" class="btn btn-link">
                                    <strong>Términos y Condiciones</strong>
                                </a>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Formas de Pago: *</label>
                            {!! Form::select('mpago', $formas_pago,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','id'=>'mpago','value'=>'{{ old("mpago") }}','data-container'=>'.main-container','placeholder'=>'Seleccione ...','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="#" class="btn btn-outline-primary btn-block animated bounceIn" id="pagar" hidden>
                        <i class="fa fa-save"></i> Guardar
                    </a>
                </div>
            </section>
            {!! Form::close() !!}


                        <div class="row justify-content-center">
                            <div class="col-md-8 col-sm-12">
                                <div class="card card-deck border-radius-10 box-shadow bg-light animated fadeIn"
                                     id="payment_card"
                                     hidden>
                                    <div class="card-body text-center">
                                        <h4 class="card-title weight-500 mb-20">Detalles de su tarjeta</h4>
                                        <h6 class="card-subtitle mb-2 text-muted mb-10">Mientras se realiza la transacción no
                                            debe recargar el navegador ni regresar atrás. Por favor espere que termine la
                                            operación</h6>
                                        {!! Form::open(['class'=>'form-horizontal', 'id'=>'add-card-form']) !!}
                                        <div class="paymentez-form" id="my-card" data-capture-name="true"
                                             data-capture-email="true" data-capture-cellphone="true" data-icon-colour="#569B29">
                                        </div>
                                        <button class="btn btn-outline-success weight-500 btn-block"><i
                                                    class="fa fa-money"></i> Proceder al Pago
                                        </button>
                                        <div id="messages_paymentez"></div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div id="loader" hidden>
                                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw text-success"></i>
                                        <span class="sr-only">Cargando...</span></div>
                                </div>
                            </div>
                        </div>










        </div>

    </div>

    @include('inscripcion.online.modals.terminos-modal')

@endsection

@push('scripts')
<script src="https://cdn.paymentez.com/js/ccapi/stg/paymentez.min.js"></script>
<script src="{{asset('themes/back/src/plugins/jquery-validation/dist/jquery.validate.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/jquery-validation/dist/localization/messages_es.min.js')}}"></script>
<script src="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.min.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script src="{{asset('plugins/paymentez/paymentez.js')}}"></script>
<script>

    $(document).ready(function (event) {


        $("#categoria_id").change(function () {
            let id = this.value;
            let token = $("input[name=token]").val();
            let talla = $("#talla");
            let circuito = $("#circuito_id");
            $("#costo").val('0.00');
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('getCategoriaCircuito')}}",
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': token},
                    dataType: 'json',
                    data: {id: id},
                    success: (response) => {
                        resolve(response);
                    },
                    error: (error) => {
                        reject(error);
                    }
                });

            });
            prom.then((response) => {
                circuito.find("option:gt(0)").remove();
                $.each(response.data, function (ind, elem) {
                    circuito.append('<option value="' + elem.circuito.id + '">' + elem.circuito.circuito + ' - ' + elem.circuito.title + '</option>');
                });
                circuito.selectpicker("refresh");

            }).catch((error) => { //error en respuest ajax
                swal(
                    ':( Lo sentimos ocurrio un error durante su petición',
                    '' + error.status + ' ' + error.statusText + '',
                    'error'
                );
//               console.log(error);
            });
        });

        //costo
        $("#circuito_id").change(function () {
            let id = this.value;
            let token = $("input[name=token]").val();
            let categoria = $("#categoria_id").val();
            let costo = $("#costo");
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('user.getCosto')}}",
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': token},
                    dataType: 'json',
                    data: {
                        circuito_id: id,
                        categoria_id: categoria
                    },
                    success: (response) => {
                        resolve(response);
                    },
                    error: (error) => {
                        reject(error);
                    }
                });

            });
            prom.then((response) => {
                costo.val(response.data)
            }).catch((error) => { //error en respuest ajax
                swal(
                    ':( Lo sentimos ocurrio un error durante su petición',
                    '' + error.status + ' ' + error.statusText + '',
                    'error'
                );
//               console.log(error);
            });
        });

        //stock camisetas
        $("#talla").change(function () {
            let id = this.value;
            let token = $("input[name=token]").val();
            let stock = $("#stock-talla");
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('user.getTallaStock')}}",
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': token},
                    dataType: 'json',
                    data: {
                        talla_id: id
                    },
                    success: (response) => {
                        resolve(response);
                    },
                    error: (error) => {
                        reject(error);
                    }
                });

            });
            prom.then((response) => {
                stock.html(response.data);
            }).catch((error) => { //error en respuest ajax
                swal(
                    ':( Lo sentimos ocurrio un error durante su petición',
                    '' + error.status + ' ' + error.statusText + '',
                    'error'
                );
//               console.log(error);
            });

        });

        //Habilitar / Desabilitar boton de pago
        $("#terminos").on('change', function (e) {
            if ($(this).is(':checked')) {
                $("#mpago").prop('disabled', false);
                $("#mpago option:eq(0)").prop('selected', true);
                $("#mpago").selectpicker("refresh");
            } else {
                $("#mpago").prop('disabled', true);
                $("#mpago").selectpicker("refresh");
                $("#payment_card").prop('hidden', true).fadeOut(1000);
                $("#pagar").prop('hidden', true).fadeOut(1000);
            }
        });

        $("#mpago").on('change', function () {
            let str = $("#mpago option:selected").text().toLowerCase();
            if (str.search("tarjeta") !== -1) {
                $("#payment_card").prop('hidden', false);
                $("#pagar").prop('hidden', true).fadeOut(1000);

            } else {
                $("#payment_card").prop('hidden', true).fadeOut(1000);
                $("#pagar").prop('hidden', false);
            }
        });


    });

    $("#form-wizard").steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "fade",
        enableFinishButton: false,
        titleTemplate: '<span class="step">#index#</span> #title#',
//        errorClass: "error",
        labels: {
            finish: "Guardar",
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
            $('finish').prop('disabled', true);
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

        errorClass: "error",
        //        errorLabelContainer: '.form-text',
        errorPlacement: function (error, element) {
            if (element.parent('.form-control').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
//            confirm: {
//                equalTo: "#password-2"
//            }
        }
    });


            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

            {{--Errorres de validacion backend--}}
            @if ($errors->any())
    let errors = [];
    let error = '';
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