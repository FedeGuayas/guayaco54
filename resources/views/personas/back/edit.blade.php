@extends('layouts.back.master')

@section('page_title','Editar Cliente')

@section('breadcrumbs')
    {!! Breadcrumbs::render('cliente-edit',$persona) !!}
@stop

@push('styles')

@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Editar Cliente</h5>
            </div>
        </div>
        {!! Form::model($persona,['route' => ['admin.cliente.update',$persona->id], 'method' => 'put', 'autocomplete'=> 'off', 'class'=>'form_noEnter','id'=>'form-update' ]) !!}

        <div class="form-row">
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('nombres','Nombres *',['class'=>'weight-600']) !!}
                {!! Form::text('nombres',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('apellidos','Apellidos *',['class'=>'weight-600']) !!}
                {!! Form::text('apellidos',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('num_doc','Num. Documento *',['class'=>'weight-600']) !!}
                {!! Form::text('num_doc',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('telefono','Teléfono',['class'=>'weight-600']) !!}
                {!! Form::text('telefono',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('email','Email',['class'=>'weight-600']) !!}
                {!! Form::email('email',null,['class'=>'form-control','style'=>'text-transform: lowercase']) !!}
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('gen','Género *',['class'=>'weight-600']) !!}
                {!! Form::select('gen', ['Masculino' => 'Masculino', 'Femenino' => 'Femenino'],null, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','required']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('fecha_nac','Fecha de Nacimiento *',['class'=>'weight-600']) !!}
                {!! Form::text('fecha_nac',null,['class'=>'form-control date-picker required','onkeydown'=>'return false;','required','data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-position'=>'right bottom']) !!}
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('discapacitado','Discapacitado ? *',['class'=>'weight-600']) !!}
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
                               @if(old('discapacitado')==="no") checked @endif checked>
                        <label class="custom-control-label weight-400"
                               for="discapacidad-no">No</label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4 col-sm-12">
                {!! Form::label('privado','Perfil privado ? *',['class'=>'weight-600']) !!}
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
                               @if(old('privado')==="no") checked @endif checked>
                        <label class="custom-control-label weight-400"
                               for="privacidad-no">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('direccion','Dirección *',['class'=>'weight-600']) !!}
            {!! Form::text('direccion',null,['class'=>'form-control','style'=>'text-transform: uppercase','required']) !!}
        </div>

        <small class="form-tex text-red-50">* Campos obligatorios</small>
        <br>

        <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
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
