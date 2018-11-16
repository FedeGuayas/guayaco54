@extends('layouts.back.master')

@section('page_title','Mantenimiento')

@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop

@push('styles')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/multiselect/css/multi-select.css')}}">
@endpush

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        {!! Form::open(['route'=>['maintenances.store'],'method' => 'post', 'autocomplete'=> 'off', 'class'=>'form_noEnter']) !!}
        <div class="col-md-6">
            {!! Form::select('users_permit[]', $trabajadores, null , ['class'=>'form-control','multiple'=>'multiple','id'=>'my-select']) !!}
        </div>

        <div class="col">
            <label for=""></label>

            {!! Form::label('status','Mantenimiento') !!}
            {!! Form::checkbox('status', null,$mantenimiento->status === \App\Maintenance::APP_ON ? true : false, ['id'=>'status'] ) !!}
        </div>

        <div class="col-md-6 mt-15">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-save"></i>
                    Guardar
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>



@endsection

@push('scripts')
<script src="{{asset('plugins/multiselect/js/jquery.multi-select.js')}}"></script>
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script>

    $('document').ready(function() {
        $('#my-select').multiSelect({
            keepOrder: true,
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