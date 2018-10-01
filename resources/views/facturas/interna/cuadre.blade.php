@extends('layouts.back.master')

@section('page_title','Arqueo')

@section('breadcrumbs')
    {!! Breadcrumbs::render('home') !!}
@stop

@section('content')

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">

        <div class="clearfix mb-20">
            <div class="pull-left">
                <h5 class="text-blue">Arqueo de caja</h5>
            </div>
        </div>

        <div class="col mb-30">
            {!! Form::open (['route' => 'admin.facturacion.arqueo', 'method' => 'GET',	'autocomplete'=> 'off',	'role' => 'search' ])!!}
            <div class="form-group row">
                <div class="col">
                    {!! Form::text('fecha',$fecha,['class'=>'form-control date-picker', 'placeholder'=>'YYYY-MM-DD','value'=>'{{ old("fecha") }}', 'data-language'=>'es','data-date-format'=> 'yyyy-mm-dd','data-clear-button'=>' true','data-position'=>'right top','id'=>'fecha','readonly']) !!}
                </div>
                {{--<div class="col">--}}
                    {{--{!! Form::select('usuario', $usuarios,$usuario, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'usuario','placeholder'=>'Seleccione ...', 'data-live-search'=>'true','data-container'=>'.main-container']) !!}--}}
                {{--</div>--}}
                <div class="col">
                    {!! Form::select('escenario', $escenarios,$escenario, ['class'=>'selectpicker show-tick form-control','data-style'=>'btn-outline-primary','id'=>'escenario','placeholder'=>'Seleccione ...', 'data-live-search'=>'true','data-container'=>'.main-container']) !!}
                </div>
                <div class="col">
                    {!! Form::submit('Filtrar',['class'=>'btn btn-outline-primary']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="form-group pull-right">
            <a href="#" class="btn btn-outline-dark" data-toggle="tooltip" id="print_cuadre" title="Imprimir"><i class="fa fa-print"></i></a>
        </div>

        <section id="pintable">

        <div class="pd-20 bg-white border-radius-4 box-shadow">

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Usuarios</th>
                        <th>Contado</th>
                        <th>Tarjeta</th>
                        <th>Western</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cuadreArray as $c )
                        <tr>
                            <td>{{strtoupper($c['usuario'])}}</td>
                            <td>{{ $c['contado'] }}</td>
                            <td>{{ $c['tarjeta'] }}</td>
                            <td>{{ $c['western'] }}</td>
                            <td>$ {{number_format($c['valor'],2,'.',' ')}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th></th>
                        <th>$ {{number_format($total['totalContado'],2,'.',' ')}}</th>
                        <th>$ {{number_format($total['totalTarjeta'],2,'.',' ')}}</th>
                        <th>$ {{number_format($total['totalWestern'],2,'.',' ')}}</th>
                        <th>$ {{number_format($total['totalGeneral'],2,'.',' ')}}</th>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
        </section>
    </div>


@endsection

@push('scripts')
<script src="{{ asset('js/toastr_message.js') }}"></script>
<script src="{{ asset('plugins/printThisJQ/printThis.js') }}"></script>
<script>

    $(document).ready(function (event) {

        $("#print_cuadre").on('click',function (e) {
            e.preventDefault();
            let escenario=$("#escenario option:selected").text();
            let pto_cobro=''
            if ($("#escenario").val()){
                pto_cobro=escenario;
            }
            let fecha=$("#fecha").val();
            $("#pintable").printThis({
                importCSS: true,
                importStyle: true,
                removeInline: false,
//                removeInlineSelector: "*",
                header: '<h3>Cuadre de Caja.</h3><br>',
                footer: 'Guayaco Runner 2018. <br>  '+pto_cobro+' <br> '+fecha+'',
                formValues: true
            });
        })

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