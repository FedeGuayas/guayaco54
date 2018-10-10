<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Inscripción</title>

    <link rel="stylesheet" href="css/recibo-pdf.css">
    <link href="themes/back/src/plugins/bootstrap-4.0.0/dist/css/bootstrap.css" rel="stylesheet">

</head>
<body>

<div class="header">


    <div class="descripcion"><h4>Registro # {{sprintf("%'.04d",$inscription->id)}}</h4></div>


</div>

<div class="contenido">

    <div class="watermark-text"></div>

    <section class="corredor">
        <h5>CORREDOR</h5>
        <table cellpadding="3">
            <tbody>
            <tr>
                <th>Nombres y Apellidos</th>
                <td>{{$inscription->persona->getFullName()}}</td>
            </tr>
            <tr>
                <th>Identificación</th>
                <td>{{$inscription->persona->num_doc}}</td>
            </tr>
            <tr>
                <th>Fecha Nac.</th>
                <td>{{$inscription->persona->fecha_nac}}</td>
            </tr>
            <tr>
                <th>Edad</th>
                <td>{{$inscription->persona->getEdad()}}</td>
            </tr>
            <tr>
                <th>Sexo</th>
                <td>{{$inscription->persona->gen}}</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{$inscription->persona->telefono}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$inscription->persona->email}}</td>
            </tr>
            <tr>
                <th>Fecha Inscripción</th>
                <td>{{$inscription->created_at}}</td>
            </tr>
            </tbody>
        </table>
    </section>

    <section class="carrera">
        <h5>INFORMACIÓN INSCRIPCIÓN</h5>
        <table cellpadding="3">
            <tbody>
            <tr>
                <th>Categoría</th>
                <td>{{$inscription->producto->categoria->categoria}}</td>
            </tr>
            <tr>
                <th>Circuito</th>
                <td>{{$inscription->producto->circuito->circuito}}</td>
            </tr>
            <tr>
                <th>Número</th>
                <td>{{$inscription->num_corredor}}</td>
            </tr>
            @if ($inscription->talla)
                <tr>
                    <th>Talla Camiseta</th>
                    <td>
                        {{$inscription->talla->talla}}
                    </td>
                </tr>
                <tr>
                    <th>Color Camiseta</th>
                    <td>
                        {{$inscription->talla->color}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </section>


    <section class="facturacion">
        <table class="table">
            <caption>FACTURACION:</caption>
            <thead style="background-color: #a3acb1">
            <tr>
                <th>Nombres</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                {{--<th>Dirección</th>--}}
                <th>Email</th>
                <th>Valor</th>
                <th>Descuento</th>
                <th>Total</th>
            </tr>

            </thead>
            <tbody>
            @if ($inscription->factura)
            <tr>
                <td>{{$inscription->factura->nombre}}</td>
                <td>{{$inscription->factura->identificacion}}</td>
                <td>{{$inscription->factura->telefono}}</td>
{{--                <td>{{$inscription->factura->direccion}}</td>--}}
                <td>{{$inscription->factura->email}}</td>
                <td>$ {{number_format($inscription->factura->subtotal,'2','.',' ')}}</td>
                <td>$ {{number_format($inscription->factura->descuento,'2','.',' ')}}</td>
                <td class="precio_total">$ {{number_format($inscription->factura->total,'2','.',' ')}}</td>
            </tr>
                @else
                <tr class="text-center">
                    <th colspan="8">Información no registrada</th>
                </tr>


                @endif
            </tbody>


        </table>
    </section>

    <br><br><br>

    <div class="cancelado">
        <table class="table">
            <caption>CANCELADO:</caption>
            <thead>
            <tr>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$inscription->factura->updated_at->formatLocalized('%d %B %Y')}}</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="note">
    <p>
        NOTA: Las inscripciones son intransferibles y los valores no serán reembolsables por ningún motivo.
    </p>
</div>

<div class="footer">
    <em>

        Oficina: José Mascote 1103 y Luque. Telfs: 2367856 - 2531488. <strong>https://guayaco-runner.fedeguayas.com.ec</strong>.
            <br>
            <strong>email: fdg@fedeguayas.com.ec</strong>


        <br>
        Guayaquil - Ecuador
    </em>

</div>

</body>
</html>