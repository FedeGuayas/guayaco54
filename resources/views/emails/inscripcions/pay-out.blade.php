@component('mail::message')
# Confirmación de pago

Gracias por realizar el pago de su inscripción en la carrera Guayaco Runner

Información de la inscripcion:

Inscrito: {{$inscripcion->persona->getFullname()}}

Circuito: {{$inscripcion->producto->circuito->circuito}} ({{$inscripcion->producto->circuito->title}})

Categoría: {{$inscripcion->producto->categoria->categoria}}

Referencia de pago:

ID: {{$inscripcion->factura->payment_id}}

Valor: $ {{$inscripcion->factura->total}}

Puede imprimir sus registros de inscripción aquí.
@component('mail::button', ['url' =>  route('user.getComprobantes')])
    Inscripciones
@endcomponent

Nota: Ud debe presentar su identificación el día de retirar el kit.



Gracias,<br>
{{ config('app.name') }}

@component('mail::panel')
    Este e-mail se ha generado automáticamente por el sistema. Por favor no respondas a esta dirección de e-mail. Si ud tiene dudas o necesita ayuda contacta con nosotros.
@endcomponent

@endcomponent
