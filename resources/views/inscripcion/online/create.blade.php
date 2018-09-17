@extends('layouts.back.master')

@section('page_title','Inscribirse')

@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop


@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('themes/back/src/plugins/jquery-steps/jquery.steps.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <h4 class="text-info">Siga los pasos</h4>
            <p class="mb-30 font-14">Para inscribirse en la carrera</p>
        </div>

        <div class="form-text text-info small">Si desea inscribir a un amigo
            <a href="{{route('getProfile')}}#asociados" class="weight-600"><i class="ion-man"></i>, click aqui
            </a> para cargar al  Asociado
        </div>


        <div class="wizard-content">
            {!! Form::open(['route' => 'personas.store', 'method' => 'post', 'autocomplete'=> 'off', 'class'=>'tab-wizard wizard-circle wizard','id'=>'form-wizard' ]) !!}
            {!! Form::hidden('asociado_id',null,['id'=>'asociado_id']) !!}

            {{--Paso 1--}}


            <h5>Datos de la carrera</h5>
            <section>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Categorías: *</label>
                            {!! Form::select('categoria_id', $categorias,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','id'=>'categoria_id','value'=>'{{ old("categoria_id") }}','required']) !!}
                            <small class="form-text text-muted">Seleccione la categoría</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Circuitos: *</label>
                            {!! Form::select('circuito_id',[] ,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','placeholder'=>'Seleccione ...','id'=>'circuito_id','value'=>'{{ old("circuito_id") }}','required']) !!}
                            <small class="form-text text-muted">Seleccione el circuito</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Talla camiseta: *</label>
                            {!! Form::select('talla', $tallas,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'talla','value'=>'{{ old("talle") }}','required']) !!}
                            <small class="form-text text-muted">Seleccione la talla </small>
                        </div>
                    </div>
                </div>

                <small class="form-text text-danger"> * Campos obligatorios</small>

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

    $(document).ready(function (event) {

        $("#categoria_id").change(function () {
            let id=this.value;
            let token= $("input[name=token]").val();
            let circuito=$("#circuito_id");
           let prom=new Promise ((resolve,reject)=>{
               $.ajax({
                   url:"{{route('getCategoriaCircuito')}}",
                   type: "GET",
                   headers: {'X-CSRF-TOKEN': token},
                   dataType:'json',
                   data:{id:id},
                   success:(response)=>{
                       resolve(response);
                   },
                   error:(error)=>{
                       reject(error);
                   }
               });


           });

           prom.then((response)=>{
               circuito.find("option:gt(0)").remove();
               $.each(response.data, function (ind, elem) {
                   circuito.append('<option value="' + elem.circuito.id + '">' + elem.circuito.circuito + ' - ' + elem.circuito.title + '</option>');
               });
               circuito.selectpicker("refresh");
               circuito.val("option:eq(0)").prop('selected', true);

           }).catch((error)=>{ //error en respuest ajax
               swal(
                   ':( Lo sentimos ocurrio un error durante su petición',
                   '' + error.status + ' ' + error.statusText + '',
                   'error'
               );
//               console.log(error);
           });

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


            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
    @endif
    {{-- FIN Alertas con Toastr--}}

</script>
@endpush