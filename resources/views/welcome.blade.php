@extends('layouts.front.app')

@section('content')


    {{--<section class="engine"><a href="https://mobiri.se/a">online web builder</a></section>--}}
    <section class="carousel slide cid-r10hLn8S2c" data-interval="false" id="slider1-9">


        <div class="full-screen">
            <div class="mbr-slider slide carousel" data-pause="true" data-keyboard="false" data-ride="carousel"
                 data-interval="4000">
                <ol class="carousel-indicators">
                    <li data-app-prevent-settings="" data-target="#slider1-9" class=" active" data-slide-to="0"></li>
                    <li data-app-prevent-settings="" data-target="#slider1-9" data-slide-to="1"></li>
                    <li data-app-prevent-settings="" data-target="#slider1-9" data-slide-to="2"></li>
                    <li data-app-prevent-settings="" data-target="#slider1-9" data-slide-to="3"></li>
                    <li data-app-prevent-settings="" data-target="#slider1-9" data-slide-to="4"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item slider-fullscreen-image active" data-bg-video-slide="false"
                         style="background-image: url({{asset('themes/front/assets/images/mg-8742-1140x550.jpg')}});">
                        <div class="container container-slide">
                            <div class="image_wrapper">
                                <div class="mbr-overlay"></div>
                                <img src="{{asset('themes/front/assets/images/mg-8742-1140x550.jpg')}}">
                                <div class="carousel-caption justify-content-center">
                                    <div class="col-10 align-center"><h2 class="mbr-fonts-style display-1">GUAYACO
                                            RUNNER</h2>
                                        <p class="lead mbr-text mbr-fonts-style display-5">La mañana del domingo 5 de
                                            octubre del 2014 se realizó la primera edición de la carrera “Guayaco
                                            Runner”</p>
                                        @if (!Auth::check())
                                            <div class="mbr-section-btn">
                                                <a class="btn display-4 btn-white-outline" href="{{ url('/login') }}">
                                                    <span class="mbri-login mbr-iconfont mbr-iconfont-btn"></span>INICIAR
                                                    SESSION
                                                </a>
                                                <a class="btn  display-4 btn-secondary-outline"
                                                   href="{{ url('/register') }}">
                                                    <span class="mbri-edit mbr-iconfont mbr-iconfont-btn"></span>REGISTRAME
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slider-fullscreen-image" data-bg-video-slide="false"
                         style="background-image: url({{asset('themes/front/assets/images/12141111-991893447515906-637985992784650844-o-1140x550.jpg')}});">
                        <div class="container container-slide">
                            <div class="image_wrapper">
                                <div class="mbr-overlay"></div>
                                <img src="{{asset('themes/front/assets/images/12141111-991893447515906-637985992784650844-o-1140x550.jpg')}}">
                                <div class="carousel-caption justify-content-center">
                                    <div class="col-10 align-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slider-fullscreen-image" data-bg-video-slide="false"
                         style="background-image: url({{asset('themes/front/assets/images/mg-8675-1140x550.jpg')}});">
                        <div class="container container-slide">
                            <div class="image_wrapper">
                                <div class="mbr-overlay"></div>
                                <img src="{{asset('themes/front/assets/images/mg-8675-1140x550.jpg')}}">
                                <div class="carousel-caption justify-content-center">
                                    <div class="col-10 align-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slider-fullscreen-image" data-bg-video-slide="false"
                         style="background-image: url({{asset('themes/front/assets/images/mg-8741-1140x550.jpg')}});">
                        <div class="container container-slide">
                            <div class="image_wrapper">
                                <div class="mbr-overlay"></div>
                                <img src="{{asset('themes/front/assets/images/mg-8741-1140x550.jpg')}}">
                                <div class="carousel-caption justify-content-center">
                                    <div class="col-10 align-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item slider-fullscreen-image" data-bg-video-slide="false"
                         style="background-image: url({{asset('themes/front/assets/images/mg-8863-1140x550.jpg')}});">
                        <div class="container container-slide">
                            <div class="image_wrapper">
                                <div class="mbr-overlay"></div>
                                <img src="{{asset('themes/front/assets/images/mg-8863-1140x550.jpg')}}">
                                <div class="carousel-caption justify-content-center">
                                    <div class="col-10 align-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a data-app-prevent-settings="" class="carousel-control carousel-control-prev" role="button"
                   data-slide="prev" href="#slider1-9"><span aria-hidden="true"
                                                             class="mbri-left mbr-iconfont"></span><span
                            class="sr-only">Previous</span></a><a data-app-prevent-settings=""
                                                                  class="carousel-control carousel-control-next"
                                                                  role="button" data-slide="next"
                                                                  href="#slider1-9"><span
                            aria-hidden="true" class="mbri-right mbr-iconfont"></span><span class="sr-only">Next</span></a>
            </div>
        </div>

    </section>

    <section class="features3 cid-r1bGIPQ4vI" id="features3-15">


        <div class="container">
            <div class="media-container-row">
                <div class="card p-3 col-12 col-md-6 col-lg-3">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/mg-8847-492x237.jpg')}}" alt="" title="">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title mbr-fonts-style display-7">2014</h4>
                            <p class="mbr-text mbr-fonts-style display-7">
                                La mañana del domingo 5 de octubre del 2014 se realizó la primera edición de la carrera
                                “Guayaco Runner”
                            </p>
                        </div>
                        <div class="mbr-section-btn text-center">
                            <a href="#" class="btn btn-primary display-4">Leer Más</a>
                        </div>
                    </div>
                </div>

                <div class="card p-3 col-12 col-md-6 col-lg-3">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/gr-2015.jpg')}}" alt="" title="">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title mbr-fonts-style display-7">
                                2015 </h4>
                            <p class="mbr-text mbr-fonts-style display-7">
                                La mañana del domingo 4 de octubre del 2015 se realizó la segunda edición de la carrera
                                “Guayaco Runner
                            </p>
                        </div>
                        <div class="mbr-section-btn text-center">
                            <a href="https://mobirise.com" class="btn btn-primary display-4">Leer Más</a>
                        </div>
                    </div>
                </div>

                <div class="card p-3 col-12 col-md-6 col-lg-3">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/gr-2016.png')}}" alt="" title="">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title mbr-fonts-style display-7">
                                2016 </h4>
                            <p class="mbr-text mbr-fonts-style display-7">Fecha: Domingo 02 de octubre a las 7h30.
                                <br>
                                <br>Distancia: 5Km, 3Km,2Km y 1Km.</p>
                        </div>
                        <div class="mbr-section-btn text-center">
                            <a href="#" class="btn btn-primary display-4">Leer Más</a>
                        </div>
                    </div>
                </div>

                <div class="card p-3 col-12 col-md-6 col-lg-3">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/gr-2017.png')}}" alt="" title="">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title mbr-fonts-style display-7">
                                2017 </h4>
                            <p class="mbr-text mbr-fonts-style display-7">Fecha: Domingo 29 de Octubre a las 07h30.
                                <br>
                                <br>Distancia: 5K, 3K y 1K.</p>
                        </div>
                        <div class="mbr-section-btn text-center">
                            <a href="#" class="btn btn-primary display-4">Leer Más</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features17 cid-r10Nu0fpa6" id="features17-g">

        <div class="container-fluid">
            <div class="media-container-row">
                <div class="card p-3 col-12 col-md-6 col-lg-4">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/sin-titulo-3-1-454x454.png')}}"
                                 alt="Recorrido 1K"
                                 title="Recorrido 1K" style="max-height: 250px;">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title pb-3 mbr-fonts-style display-7">
                                LOS LUCHADORES:
                            </h4>

                            <p class="mbr-text mbr-fonts-style display-7">1K Niños (4 a 12 años)</p>

                            <p class="mbr-text mbr-fonts-style display-7">1K Discapacitados  (Deporte Adaptado)</p>

                            <p class="mbr-text mbr-fonts-style display-7">1K Adulto mayor (&gt; 65 años)</p>

                            <p class="mbr-text mbr-fonts-style display-7">1K Categoría Abierta (13 -64 años)</p>

                            <div class="mbr-section-btn text-center">
                                <span class="badge badge-info display-4">$ 10.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-3 col-12 col-md-6 col-lg-4">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="{{asset('themes/front/assets/images/recorrido-5k-454x243.png')}}"
                                 alt="Recorrido 5K"
                                 title="Recorrido 5K" style="max-height: 250px;">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title pb-3 mbr-fonts-style display-7">
                                LOS INVENCIBLES:
                            </h4>
                            <p class="mbr-text mbr-fonts-style display-7">5K Discapacitados  (Deporte Adaptado)</p>

                            <p class="mbr-text mbr-fonts-style display-7">5K Adulto mayor (&gt; 65 años)</p>

                            <p class="mbr-text mbr-fonts-style display-7">5K Categoría Abierta (13 -64 años)</p>

                            <div class="mbr-section-btn text-center">
                                <span class="badge badge-info display-4">$ 10.00</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>

    <section class="features3 cid-r1bGIPQ4vI" id="reglamento">

        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">Reglamento&nbsp;</h2>
        {{--pd-20 bg-white border-radius-4 box-shadow mb-30--}}


        <div class="container-fluid">
            <div class="media-container-row">
                <div class="p-5 bg-white">
                    @include('shared.reglamento_2018')
                </div>

            </div>
        </div>

    </section>

    <section class="features3 cid-r1bGIPQ4vI" id="terminos">

        <h2 class="mbr-section-title pb-3 align-center mbr-fonts-style display-2">Términos y Condiciones&nbsp;</h2>

        <div class="container-fluid">
            <div class="media-container-row">

                <div class="p-5 bg-white">

                    <h5 class="mbr-section-title mbr-text text-primary">Términos y condiciones de inscripción a la
                        Carrera Guayaco Runner V edición
                        (2018) </h5>
                    <p>

                    <dl>
                        <li>
                            Este sistema es exclusivamente para inscripción y pago del registro en la carrera Guayaco
                            Runner
                            V edición.
                        </li>
                        <li>
                            El kit incluye: camiseta, bolso y medalla de participación.
                        </li>
                        <li>
                            Los kits sólo pueden ser retirados el día sábado 17 de noviembre a partir de las 09h00 am
                            hasta
                            15h00 en el Coliseo de Vóley en la Explanada del Estadio Modelo (Av. De las Américas y Av.
                            Kennedy) presentando la Cédula de identidad y el ticket de inscripción.
                        </li>
                        <li>
                            En caso de requerir cambio de talla, puede hacerlo en la mesa de información.
                        </li>
                        <li>
                            Todo ticket que no se encuentre en estado PAGADO no tendrá derecho a reclamación del kit, el
                            usuario puede cancelar en efectivo su inscripción hasta el 17 de noviembre del 2018.
                        </li>
                        <li>
                            Los participantes están sujetos al Reglamento de la carrera publicado en la Página web
                            institucional. <a href="https://fedeguayas.com.ec" target="_blank">www.fedeguayas.com.ec</a>
                        </li>
                        <li>
                            Las personas con discapacidad y/ó tercera edad que deseen acceder al 50% de descuento,
                            deberán
                            acercarse a realizar su inscripción de manera presencial portando su cédula de identidad o
                            carnet del Conadis.
                        </li>
                        <li>
                            La factura electrónica llegará 24 horas después de realizado el pago al correo electrónico
                            especificado en los datos de facturación.
                        </li>
                    </dl>
                    </p>
                    <h5 class="mbr-section-title mbr-text text-primary">Medios de pago</h5>
                    <p>
                    <dl>
                        <li>
                            El sistema acepta como medios de pago tarjetas de crédito: Diners Club, Discover, Visa,
                            Mastercard, Maestro y Electrón de débito y crédito como MasterCard, Visa, Electrón.
                        </li>
                        <li>
                            El uso, condiciones de pago y otras condiciones aplicables a las tarjetas de crédito, son de
                            exclusiva responsabilidad de su emisor.
                        </li>
                    </dl>
                    </p>
                    <h5 class="mbr-section-title mbr-text text-primary">Derecho de retracto y devoluciones</h5>
                    <p>
                    <dl>
                        <li>
                            No aplica derecho de Retracto.
                        </li>
                        <li>
                            Una vez emitida la factura no se aceptan devoluciones.
                        </li>
                    </dl>
                    </p>

                </div>

            </div>

        </div>

    </section>

    <section class="mbr-section form4 cid-r0ZXsKa1l9" id="form4-6">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-md-6">
                    <div class="google-map">
                        <iframe frameborder="0" style="border:0"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0Dx_boXQiwvdz8sJHoYeZNVTdoWONYkU&amp;q=place_id:ChIJ5XKj7XVuLZARjJ_SuYrOT2Q"
                                allowfullscreen=""></iframe>
                    </div>
                </div>

                {{--<div class="col-md-6">--}}
                    {{--<h3 class="pb-3 align-left mbr-fonts-style display-2">Contacto</h3>--}}
                    {{--<div>--}}
                        {{--<div class="icon-block pb-3">--}}
                        {{--<span class="icon-block__icon">--}}
                            {{--<span class="mbri-letter mbr-iconfont"></span>--}}
                        {{--</span>--}}
                            {{--<h4 class="icon-block__title align-left mbr-fonts-style display-5">No dude en contactarnos--}}
                                {{--<div><br></div>--}}
                            {{--</h4>--}}
                        {{--</div>--}}
                        {{--<div class="icon-contacts pb-3">--}}
                            {{--<h5 class="align-left mbr-fonts-style display-7">Listo para atenderle</h5>--}}
                            {{--<p class="mbr-text align-left mbr-fonts-style display-7">--}}
                                {{--Dirección: {{$config ? $config->direccion : ''}} <br>--}}
                                {{--Teléfonos: {{$config ? $config->telefonos : ''}} <br>--}}
                                {{--Email: {{$config ? $config->email : ''}}--}}
                            {{--</p>--}}
                    {{--</div>--}}

                    {{--<div data-form-type="formoid">--}}
                        {{--<div data-form-alert="" hidden="">¡Gracias por contactarnos!</div>--}}

                        {{--<form class="block mbr-form" action="#" method="post"--}}
                              {{--data-form-title="Formulario de Contacto">--}}
                            {{--{!! csrf_field() !!}--}}
                            {{--<input type="hidden" name="email" data-form-email="true"--}}
                                   {{--value="{{$config ? $config->email : 'info@fedeguayas.com.ec'}}"--}}
                                   {{--data-form-field="Email">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-6 multi-horizontal" data-for="name">--}}
                                    {{--<small class="form-text"> Su nombre</small>--}}
                                    {{--<input type="text" class="form-control input" name="name" data-form-field="Name"--}}
                                           {{--placeholder="Su Nombre" required="" id="name-form4-6"--}}
                                           {{--value="{{Auth::check() ? Auth::user()->getFullName() : ''}}">--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6 multi-horizontal" data-for="phone">--}}
                                    {{--<small class="form-text"> Teléfono de contacto</small>--}}
                                    {{--<input type="number" class="form-control input" name="phone" data-form-field="Phone"--}}
                                           {{--placeholder="Teléfono" required="" id="phone-form4-6">--}}
                                {{--</div>--}}
                                {{--<div class="col-md-12" data-for="email">--}}
                                    {{--<small class="form-text"> Su correo electrónico</small>--}}
                                    {{--<input type="text" class="form-control input" name="email" data-form-field="Email"--}}
                                           {{--placeholder="Email" required="" id="email-form4-6"--}}
                                           {{--value="{{Auth::check() ? Auth::user()->email : ''}}">--}}
                                {{--</div>--}}
                                {{--<div class="col-md-12" data-for="message">--}}
                                    {{--<small class="form-text"> El mensaje</small>--}}
                                    {{--<textarea class="form-control input" name="message" rows="3"--}}
                                              {{--data-form-field="Message"--}}
                                              {{--placeholder="Mensaje" style="resize:none; text-transform: uppercase;"--}}
                                              {{--id="message-form4-6" required></textarea>--}}
                                {{--</div>--}}
                                {{--<div class="input-group-btn col-md-12" style="margin-top: 10px;">--}}
                                    {{--@if (Auth::check())--}}
                                        {{--<button href="" type="submit" class="btn btn-primary btn-form display-4">ENVIAR--}}
                                            {{--MENSAJE--}}
                                        {{--</button>--}}
                                    {{--@else--}}
                                        {{--<h5 class=" align-left text-primary ">Debe iniciar sesión para enviar el--}}
                                            {{--mensaje</h5>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</form>--}}

                    {{--</div>--}}
                {{--</div>--}}

            </div>
        </div>
    </section>


@endsection


