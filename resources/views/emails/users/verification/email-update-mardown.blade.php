@component('mail::message')
# Actualizacion de email

Su correo electrónico ha sido cambiado en el sistema.

Para poder acceder nuevamente antes debe verificar su email dando click en el boton siguiente.


@component('mail::button', ['url' => route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email)])
    Haga clic aquí para verificar su cuenta
@endcomponent

Este correo es generado automáticamente favor no contestarlo

Gracias,<br>
{{ config('app.name') }}
@endcomponent
