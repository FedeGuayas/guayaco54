@extends('layouts.back.master')

@section('page_title','Inscribir cliente')

@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop


@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="clearfix">
            <h4 class="text-info">Inscribir al Cliente</h4>
        </div>

            {!! Form::open(['method' => 'post', 'autocomplete'=> 'off', 'class'=>'form_noEnter']) !!}

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
                            <label for="deporte_id" class="weight-600">Deportes: </label>
                            {!! Form::select('deporte_id', $deportes,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'deporte_id','value'=>'{{ old("deporte_id") }}', 'data-live-search'=>'true','data-container'=>'.main-container','placeholder'=>'Seleccione ...','disabled']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Talla camiseta: </label>
                            <span class="badge badge-pill badge-primary pull-right" id="stock-talla" data-toggle="tooltip" data-placement="top" title="Stock">0</span>
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

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombres :</label>
                            {!! Form::text('nombres',$persona->nombres,['class'=>'form-control required','style'=>'text-transform: uppercase', 'value'=>'{{ old("nombres") }}','required','id'=>'nombres']) !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Apellidos :</label>
                            {!! Form::text('apellidos',$persona->apellidos,['class'=>'form-control required','style'=>'text-transform: uppercase','value'=>'{{ old("apellidos") }}','required','id'=>'apellidos']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Identificación: </label>
                            {!! Form::text('num_doc',$persona->num_doc,['class'=>'form-control required','style'=>'text-transform: uppercase','value'=>'{{ old("num_doc") }}','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email :</label>
                            {!! Form::email('email',$persona->email ? $persona->email : Auth::user()->email,['class'=>'form-control required','placeholder'=>'Email','style'=>'text-transform: lowercase','value'=>'{{ old("email") }}','required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teléfono :</label>
                            {!! Form::text('telefono',$persona->telefono ? $persona->telefono : '',['class'=>'form-control','value'=>'{{ old("telefono") }}']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Dirección: </label>
                            {!! Form::text('direccion',$persona->direccion ? $persona->direccion : '',['class'=>'form-control','value'=>'{{ old("direccion") }}']) !!}
                        </div>
                    </div>
                </div>

            </section>


            {{--Paso 3--}}
            <h5>Pago y Términos</h5>
            <section>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="categoria_id" class="weight-600">Formas de Pago: *</label>
                            {!! Form::select('mpago', $formas_pago,null, ['class'=>'selectpicker show-tick form-control required','data-style'=>'btn-outline-primary','id'=>'mpago','value'=>'{{ old("mpago") }}','data-container'=>'.main-container','placeholder'=>'Seleccione ...']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <label class="weight-600">Términos y Condiciones</label>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="terminos" disabled>
                            <label class="custom-control-label" for="terminos">Confirme que ha leido y esta de acuerdo con los <a href="#terminos-modal" data-toggle="modal" class="btn btn-link"> <strong>Términos y Condiciones</strong></a></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="#" class="btn btn-success btn-block" id="pagar" hidden>
                        Pagar
                    </a>
                </div>
            </section>

            {!! Form::close() !!}

        </div>

@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(document).ready(function () {

        $(".form_noEnter").keypress(function (e) {
            if (e.which === 13) {
                return false;
            }
        });


    });

    $(document).ready(function (event) {

        $("#categoria_id").change(function () {
            let id = this.value;
            let token = $("input[name=token]").val();
//            let deporte = $("#deporte_id");
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
                if (response.deportista === false) { //no es deportista
                    deporte.val("option:eq(0)").prop('selected', true);
                    deporte.prop('disabled', true);
                    deporte.selectpicker('refresh');

                    talla.prop('disabled', false);
                    talla.selectpicker("refresh");
                } else { //es deportista
                    swal(
                        ' Esta opción solo se debe utilizar para participar, no tendrá costo pero no se entregará el Kit',
                        '',
                        'warning'
                    );
                    deporte.prop('disabled', false);
                    deporte.selectpicker("refresh");

                    talla.val("option:eq(0)").prop('selected', true);
                    talla.prop('disabled', true);
                    talla.selectpicker('refresh');
                }

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
                    url: "{{route('user.updateTallaStock')}}",
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

        $("#mpago").on('change',function () {
            if ($(this).val()!==''){
                $("#terminos").prop('disabled',false);
            }else {
                $("#terminos").prop('disabled',true);
                $("#pagar").prop('hidden', true);

            }
        });

        //Habilitar / Desabilitar boton de pago
        $("#terminos").on('change',function (e) {
            if ($(this).is(':checked')) {
//                $("#pagar").prop('disabled', false);
                $("#pagar").prop('hidden', false);
            }
            else {
                $("#pagar").prop('hidden', true);
            }
        });


    });


            {{--Alertas con Toastr--}}
            @if(Session::has('message_toastr'))
    let type = "{{ Session::get('alert-type') }}";
    let text_toastr = "{{ Session::get('message_toastr') }}";
    showAlert(type, text_toastr);
            @endif
            {{-- FIN Alertas con Toastr--}}
            {{--errorres de validacion--}}
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