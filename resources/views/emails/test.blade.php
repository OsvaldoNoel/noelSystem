<!DOCTYPE html>
<html>

<head>
    <title>Prueba Brevo</title>
</head>

<body>
    <h1>¡Prueba exitosa!</h1>
    <p>Sistema: {{ config('app.name') }}</p>
    <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>

    @if (Auth::check())
        <p>Usuario: {{ Auth::user()->email }}</p>
    @endif
</body>

</html>
