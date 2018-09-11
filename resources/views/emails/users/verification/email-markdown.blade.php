@component('mail::message')
{{--# {{ $user->first_name.' '.$user->last_name}}--}}

Un último paso!

Su datos para acceder son los siguientes:

usuario: {{ $user->email}}<br>
contraseña: especificada por Ud durante el registro.

Para poder acceder primeramente debe verificar su email dando click en el boton siguiente.

@component('mail::button', ['url' => route('email-verification.check', $user->verification_token) . '?email=' . urlencode($user->email),'color'=>'green'])
Haga clic aquí para verificar su cuenta
@endcomponent

Nota: Una vez que haya ingresado al sistema podrá completar los datos de su perfil en su menu de usuario

Gracias,<br>
{{ config('app.name') }}

@endcomponent
