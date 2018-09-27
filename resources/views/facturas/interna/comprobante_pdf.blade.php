@extends('layouts.back.master')

@section('page_title','Comprobante')



@push('styles')

    <link rel="stylesheet" type="text/css"
    href="{{asset('css/comprobante_print.css')}}">
@endpush

@section('content')

    <div class="invoice-wrap mb-30">

        <div class="invoice-box"  title="Clic para Imprimir">

            {{--<div class="watermark">--}}
                <div id="watermark-text">Guayaco Runner 2018</div>
            {{--</div>--}}

            <div class="invoice-header">
                <div class="logo text-center mb-0">
                    <img src="{{asset('images/fdg-logo.png')}}" alt="">
                </div>
            </div>
            <h4 class="text-center mb-0  weight-600">Comprobante</h4>
            <div class="row pb-30">
                <div class="col-md-6">
                    <h5 class="mb-15">Nombre del CLiente</h5>
                    <p class="font-14 mb-5">Fecha de emisión: <strong class="weight-600">{{$factura->created_at->formatLocalized('%d %B %Y')}}</strong></p>
                    <p class="font-14 mb-5">Comprobante No: <strong class="weight-600">{{sprintf("%'.04d",$factura->numero)}}</strong></p>
                </div>
                <div class="col-md-6">
                    <div class="text-right">
                        <p class="font-14 mb-5">{{$factura->nombre}}</p>
                        <p class="font-14 mb-5">{{$factura->direccion}}</p>
                        <p class="font-14 mb-5">{{$factura->telefono}}</p>
                        <p class="font-14 mb-5">{{$factura->email}}</p>
                    </div>
                </div>
            </div>
            <div class="invoice-desc pb-30">
                <div class="invoice-desc-head clearfix">
                    <div class="invoice-sub">Evento</div>
                    <div class="invoice-rate">Valor</div>
                    <div class="invoice-rate">Descuento</div>
                    <div class="invoice-subtotal">Subtotal</div>
                </div>
                <div class="invoice-desc-body">
                    <ul>
                        <li class="clearfix">
                            <div class="invoice-sub">Guayaco Runner 2018</div>
                            <div class="invoice-rate">$ 10.00</div>
                            <div class="invoice-hours">$ 3.00</div>
                            <div class="invoice-subtotal"><span class="weight-600">$ 7.00</span></div>
                        </li>
                    </ul>
                </div>
                <div class="invoice-desc-footer">
                    <div class="invoice-desc-head clearfix">
                        <div class="invoice-sub">Cancelado por</div>
                        {{--<div class="invoice-rate">Due By</div>--}}
                        <div class="invoice-subtotal">TOTAL</div>
                    </div>
                    <div class="invoice-desc-body">
                        <ul>
                            <li class="clearfix">
                                <div class="invoice-sub">
                                    <p class="font-14 mb-5">Usuario: <strong class="weight-600">Ximena Riofrio</strong></p>
                                    <p class="font-14 mb-5">Pto Cobro: <strong class="weight-600">Estadio Modelo</strong></p>
                                    <p class="font-14 mb-5">Fecha: <strong class="weight-600">{{$factura->created_at->formatLocalized('%d %B %Y')}}</strong></p>
                                </div>
                                {{--<div class="invoice-rate font-20 weight-600">10 Jan 2018</div>--}}
                                <div class="invoice-subtotal"><span class="weight-600 font-24 text-danger">$ 7.00 </span></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center text-muted pb-20">
                <strong><em>Oficina: José Mascote 1103 y Luque. Telfs: 2367856 - 2531488.  email: info@fedeguayas.com.ec</em></strong><em><br>
                    <strong> Casilla 836 Telegramas y Cables - FEDEGUAYAS. Guayaquil - Ecuador</strong></em><strong></strong>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{asset('plugins/printThisJQ/printThis.js')}}"></script>
<script>
    $(".invoice-box").click(function () {
        //Hide all other elements other than printarea.
        $(".invoice-box").printThis({
            debug: false,               // show the iframe for debugging
            importCSS: true,            // import page CSS
            importStyle: true,         // import style tags
            printContainer: true,       // grab outer container as well as the contents of the selector
//            loadCSS: "",  // path to additional css file - use an array [] for multiple
            pageTitle: "Hola",              // add title to print page
            removeInline: false,        // remove all inline styles from print elements
            printDelay: 333,            // variable print delay
            header: null,               // prefix to html
            footer: null,               // postfix to html
            base: false ,               // preserve the BASE tag, or accept a string for the URL
            formValues: true,           // preserve input/form values
            canvas: false,              // copy canvas elements (experimental)
//            doctypeString: "...",       // enter a different doctype for older markup
            removeScripts: false,       // remove script tags from print content
            copyTagClasses: false       // copy classes from the html & body tag

        });

    });
</script>

@endpush