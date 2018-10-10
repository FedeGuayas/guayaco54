<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Comprobante de inscripción</title>
    <link rel="stylesheet" href="css/preinscricion-comprobante-pdf.css">
    <link href="themes/back/src/plugins/bootstrap-4.0.0/dist/css/bootstrap.css" rel="stylesheet">

</head>
<body>
<br>
<table align="center" cellpadding="0" cellspacing="2" style=" width: 90%;">
    <tr>
        <th align="left">Inscripción No. {{sprintf("%'.04d",$inscription->id)}}</th>
        <th align="left">Válida Hasta: {{$inscription->created_at->addHour(48)->toDateString()}}</th>
    </tr>
    <tr>
        <td colspan="2">Forma de Pago: {{$inscription->factura->mpago->nombre}}</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Cliente: {{$inscription->persona->getFullName()}}</td>
        <th></th>
    </tr>
    <tr>
        <td colspan="2">Identificación: {{ $inscription->persona->num_doc }}</td>
        <td></td>
    </tr>


    <tr>
        <td colspan="2">Circuito: {{ $inscription->producto->circuito->circuito }}</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Categoría: {{ $inscription->producto->categoria->categoria }}</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2">Fecha Inscripción: {{$inscription->created_at->toDateString()}} </td>
        <td></td>
    </tr>

    <tr>
        <th colspan="2">VALOR A CANCELAR: $ {{number_format($inscription->factura->total,2,'.',' ')}}</th>
        <th></th>
    </tr>

</table>

<br><br>
<table align="center" border="0" style=" width: 90%;">
    <tr>
        <td style="font-style: italic ">
            <p>Ud debe presentar este comprobante para poder realizar el pago en la forma escogida al realizar su
                inscripción.</p>
        </td>
    </tr>

</table>

<div class="logo"></div>

</body>
</html>
