<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Rutas permitidas sin cambio de contraseña
        $allowedRoutes = [
            'logout',
            'password.change', // Ruta del formulario de cambio
            'password.change.update', // Ruta para actualizar la contraseña
        ];

        if (
            $user && $user->needsPasswordChange() &&
            !in_array($request->route()->getName(), $allowedRoutes)
        ) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
