@extends('layouts.back.master')

@section('page_title','Editar comprobante')

@section('breadcrumbs')
    {!! Breadcrumbs::render('comprobante-edit',$factura) !!}
@stop


@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-3">
            <h4 class="text-info">Editar Comprobante</h4>
        </div>

        {!! Form::model($factura,['route'=>['facturas.update',$factura->id],'method' => 'put', 'autocomplete'=> 'off', 'class'=>'form_noEnter']) !!}
        {{--        {!! Form::hidden('persona_id',$persona->id,['id'=>$persona->id]) !!}--}}
        <div class="row clearfix justify-content-center">

            <div class="col-md-10 col-sm-12 mb-30 pd-20 bg-white border-radius-4 box-shadow">

                            <section>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            {!! Form::label('nombre','Nombres y Apellidos*') !!}
                                            {!! Form::text('nombre',null,['class'=>'form-control','style'=>'text-transform: uppercase','required','id'=>'nombre','required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('identificacion','Identificación *') !!}
                                            {!! Form::text('identificacion', null,['class'=>'form-control','style'=>'text-transform: uppercase','id'=>'identificacion','required']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('email','Email *') !!}
                                            {!! Form::email('email',null ,['class'=>'form-control','placeholder'=>'Email','style'=>'text-transform: lowercase','id'=>'email','required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('telefono','Teléfono *') !!}
                                            {!! Form::text('telefono',null,['class'=>'form-control','id'=>'telefono','required']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('direccion','Dirección *') !!}
                                            {!! Form::text('direccion',null,['class'=>'form-control','style'=>'text-transform: uppercase','id'=>'direccion','required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox mb-15">
                                    <input type="checkbox" class="custom-control-input" id="consumidor_final">
                                    <label class="custom-control-label" for="consumidor_final">Editar Facturación                                                Final</label>
                                </div>
                                <small class="form-text text-danger"> * Campos obligatorios</small>
                            </section>



                    <div class="row pt-2">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-block btn-primary">Guardar
                                Comprobante
                            </button>
                        </div>
                    </div>

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

        let nombre = $("#nombre");
        let identificacion = $("#identificacion");
        let email = $("#email");
        let telefono = $("#telefono");
        let direccion = $("#direccion");

        $(".form_noEnter").keypress(function (e) {
            if (e.which === 13) {
                return false;
            }
        });

        //Habilitar / Desabilitar boton de pago
        $("#consumidor_final").on('change', function (e) {
            if ($(this).is(':checked')) {
                nombre.val('Consumidor');
                identificacion.val('999999999');
                email.val('consumidor@final.mail');
                telefono.val('N/A');
                direccion.val('N/A');
            }
            else {
                $('.form_noEnter').trigger("reset");
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