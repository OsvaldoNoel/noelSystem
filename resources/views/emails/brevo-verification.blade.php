@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Verificación de Email Requerida

    Hola {{ $user->name }},

    Por favor verifica tu dirección de email haciendo clic en el siguiente botón:

    @component('mail::button', ['url' => $verificationUrl, 'color' => 'primary'])
        Verificar Email
    @endcomponent

    Este enlace expirará en 60 minutos. Si no realizaste esta solicitud, ignora este mensaje.

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        @endcomponent
    @endslot
@endcomponent
