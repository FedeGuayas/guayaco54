@extends('layouts.back.master')

@section('page_title','Inscribir cliente')

@section('breadcrumbs')
    {!! Breadcrumbs::render('inscripcion-create') !!}
@stop


@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-3">
            <h4 class="text-info">Inscribir al Cliente</h4>
        </div>

        {!! Form::open(['route'=>['admin.inscription.store'],'method' => 'post', 'autocomplete'=> 'off', 'class'=>'form_noEnter']) !!}
        {!! Form::hidden('persona_id',$persona->id,['id'=>$persona->id]) !!}
        <div class="row clearfix justify-content-center">

            <div class="col-md-10 col-sm-12 mb-30">

                <div class="pd-20 bg-white border-radius-4 box-shadow">
                    <h5 class="weight-500 mb-20">{{$persona->getFullName()}}</h5>
                    <p class="font-14 text-info">Edad : {{$persona->getEdad()}} años. </p>

                    <div class="tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-blue" data-toggle="tab" href="#inscripcion" role="tab"
                                   aria-selected="true">Inscripción</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-blue" data-toggle="tab" href="#factura" role="tab"
                                   aria-selected="false">Facturación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-blue" data-toggle="tab" href="#perfil" role="tab"
                                   aria-selected="false">Datos Personales</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Inscripcion-->
                            <div class="tab-pane fade show active" id="inscripcion" role="tabpanel">
                                <div class="pd-20">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('categoria_id','Categorías: *',['class'=>'weight-600']) !!}
                                                    {!! Form::select('categoria_id', $categorias,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'categoria_id','data-container'=>'.main-container','placeholder'=>'Seleccione ...','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('circuito_id','Circuitos: *',['class'=>'weight-600']) !!}
                                                    {!! Form::select('circuito_id',[] ,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','placeholder'=>'Seleccione ...','id'=>'circuito_id','data-container'=>'.main-container','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('deporte_id','Deportes: ',['class'=>'weight-600']) !!}
                                                    {!! Form::select('deporte_id', $deportes,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'deporte_id', 'data-live-search'=>'true','data-container'=>'.main-container','placeholder'=>'Seleccione ...','disabled']) !!}
                                                    <small class="form-text text-muted"> Solo si Categoría =
                                                        Deportista
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('talla','Talla camiseta: *',['class'=>'weight-600']) !!}
                                                    <span class="badge badge-pill badge-primary pull-right"
                                                          id="stock-talla" data-toggle="tooltip" data-placement="top"
                                                          title="Stock">0</span>
                                                    {!! Form::select('talla', $tallas,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'talla','placeholder'=>'Seleccione ...', 'data-live-search'=>'true','data-container'=>'.main-container','required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('descuentos','Descuentos:',['class'=>'weight-600']) !!}
                                                    {!! Form::select('descuentos', $descuentos,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'descuentos','data-container'=>'.main-container','placeholder'=>'Seleccione ...']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('mpago','Formas de Pago: *',['class'=>'weight-600']) !!}
                                                    {!! Form::select('mpago', $formas_pago,null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'mpago','data-container'=>'.main-container','placeholder'=>'Seleccione ...','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    {!! Form::label('costo','Costo:',['class'=>'weight-600']) !!}
                                                    <div class="input-group">
                                                    <span class="input-group-prepend">
                                                     <span class="btn btn-outline-secondary disabled">$</span>
                                                     </span>
                                                        {!! Form::text('costo',null, ['class'=>'form-control','id'=>'costo','placeholder'=>'0.00','readonly']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="form-text text-danger"> * Campos obligatorios</small>
                                    </section>
                                </div>
                            </div>
                            <!-- FIN Inscripcion-->
                            <!-- Facturación-->
                            <div class="tab-pane fade" id="factura" role="tabpanel">
                                <div class="pd-20">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('nombres_fact','Nombres *') !!}
                                                    {!! Form::text('nombres_fact',$persona->nombres,['class'=>'form-control','style'=>'text-transform: uppercase','required','id'=>'nombres_fact','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('apellidos_fact','Apellidos *') !!}
                                                    {!! Form::text('apellidos_fact',$persona->apellidos,['class'=>'form-control','style'=>'text-transform: uppercase','id'=>'apellidos_fact','required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('num_doc_fact','Identificación *') !!}
                                                    {!! Form::text('num_doc_fact',$persona->num_doc,['class'=>'form-control','style'=>'text-transform: uppercase','id'=>'num_doc_fact','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('email_fact','Email *') !!}
                                                    {!! Form::email('email_fact',$persona->email ? $persona->email : 'consumidor@final.mail',['class'=>'form-control','placeholder'=>'Email','style'=>'text-transform: lowercase','id'=>'email_fact','required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('telefono_fact','Teléfono *') !!}
                                                    {!! Form::text('telefono_fact',$persona->telefono ? $persona->telefono : 'N/A',['class'=>'form-control','id'=>'telefono_fact','required']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label('direccion_fact','Dirección *') !!}
                                                    {!! Form::text('direccion_fact',$persona->direccion ? $persona->direccion : 'N/A',['class'=>'form-control','style'=>'text-transform: uppercase','id'=>'direccion_fact','required']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-15">
                                            <input type="checkbox" class="custom-control-input" id="consumidor_final">
                                            <label class="custom-control-label" for="consumidor_final"> Consumidor Final</label>
                                        </div>
                                        <small class="form-text text-danger"> * Campos obligatorios</small>
                                    </section>
                                </div>
                            </div>
                            <!-- FIN Facturación-->
                            <!-- Datos Perfil-->
                            <div class="tab-pane fade" id="perfil" role="tabpanel">
                                <div class="pd-20">
                                    <section class="font-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('nombres','Nombres') !!}
                                                    {!! Form::text('nombres',$persona->nombres,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('apellidos','Apellidos') !!}
                                                    {!! Form::text('apellidos',$persona->apellidos,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('fecha_nac','Fecha de Nacimiento') !!}
                                                    {!! Form::text('fecha_nac',$persona->fecha_nac,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('edad','Edad') !!}
                                                    {!! Form::text('edad',$persona->getEdad(), ['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('gen','Género') !!}
                                                    {!! Form::text('gen',$persona->gen, ['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('num_doc','Identificación') !!}
                                                    {!! Form::text('num_doc',$persona->num_doc,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('email','Email') !!}
                                                    {!! Form::text('email',$persona->email,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    {!! Form::label('telefono','Teléfono') !!}
                                                    {!! Form::text('telefono',$persona->telefono,['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col">
                                                <div class="form-group">
                                                    {!! Form::label('direccion','Dirección') !!}
                                                    {!! Form::text('direccion',$persona->direccion, ['class'=>'form-control form-control-sm','readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <!-- FIN Datos Perfil-->
                            <div class="row pt-2">
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-block btn-primary disabled" id="guardar_inscripcion">Guardar Inscripción</button>
                                </div>
                            </div>
                        </div><!-- ./ tab-content -->
                    </div><!-- ./ tab -->
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $(document).ready(function (event) {

        let token = $("input[name=token]").val();

        let deporte = $("#deporte_id");
        let talla = $("#talla");
        let circuito = $("#circuito_id");
        let descuentos = $("#descuentos");
        let categoria = $("#categoria_id");
        let costo = $("#costo");
        let stock = $("#stock-talla");
        let guardar_inscripcion=$("#guardar_inscripcion");

        let nombres_fact=$("#nombres_fact");
        let apellidos_fact=$("#apellidos_fact");
        let num_doc_fact=$("#num_doc_fact");
        let email_fact=$("#email_fact");
        let telefono_fact=$("#telefono_fact");
        let direccion_fact=$("#direccion_fact");




        $(".form_noEnter").keypress(function (e) {
            if (e.which === 13) {
                return false;
            }
        });

        $("#categoria_id").change(function () {
            let id = this.value;
            descuentos.val("option:eq(0)").prop('selected', true);
            descuentos.selectpicker('refresh');
            costo.val('0.00');
            if (id === '') {
                circuito.find("option:gt(0)").remove();
                circuito.selectpicker("refresh");
                return false;
            }
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('getCatCir')}}",
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': token},
                    dataType: 'json',
                    data: data= {id: id},
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

                    descuentos.prop('disabled', false);
                    descuentos.selectpicker("refresh");
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

                    descuentos.val("option:eq(0)").prop('selected', true);
                    descuentos.prop('disabled', true);
                    descuentos.selectpicker("refresh");
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

        //costo update
        $("#circuito_id").change(function () {
            let circuito_id = this.value;
            let categoria_id = categoria.val();
            let descuento_id = descuentos.val();
            getCosto(categoria_id,circuito_id,descuento_id);
        });

//        //costo update for descuentos
        $("#descuentos").change(function () {
            let descuento_id = this.value;
            let circuito_id = circuito.val();
            let categoria_id = categoria.val();
            getCosto(categoria_id,circuito_id,descuento_id);
        });

        //costo
        let getCosto=function(categoria_id,circuito_id,descuento_id){
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('admin.getCosto')}}",
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': token},
                    dataType: 'json',
                    data: {
                        descuento_id:descuento_id,
                        circuito_id: circuito_id,
                        categoria_id: categoria_id
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
        };

        //stock camisetas
        $("#talla").change(function () {
            let id = this.value;
            let prom = new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{route('tallas.getStock')}}",
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

        $("#mpago").on('change', function () {
            if ($(this).val() !== '') {
                guardar_inscripcion.removeClass('disabled');
            } else {
                guardar_inscripcion.addClass('disabled');

            }
        });

        //Habilitar / Desabilitar boton de pago
        $("#consumidor_final").on('change', function (e) {
            if ($(this).is(':checked')) {
                nombres_fact.val('Consumidor').prop('readonly',true);
                apellidos_fact.val('Final').prop('readonly',true);
                num_doc_fact.val('999999999').prop('readonly',true);
                email_fact.val('consumidor@final.mail').prop('readonly',true);
                telefono_fact.val('N/A').prop('readonly',true);
                direccion_fact.val('N/A').prop('readonly',true);
            }
            else {
                nombres_fact.val('').prop('readonly',false);
                apellidos_fact.val('').prop('readonly',false);
                num_doc_fact.val('').prop('readonly',false);
                email_fact.val('').prop('readonly',false);
                telefono_fact.val('').prop('readonly',false);
                direccion_fact('').prop('readonly',false);
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