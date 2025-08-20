<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedCustom
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null): Response
    {
        $user = $request->user();

        // Rutas permitidas sin email verificado
        $allowedRoutes = [
            'logout',
            'password.change',
            'password.change.update',
            'email.verification', // Ruta de verificación de email
            'verification.verify', // Ruta de verificación por token
            'verification.resend', // Ruta para reenviar verificación
        ];

        if (
            $user && !$user->hasVerifiedEmail() &&
            !in_array($request->route()->getName(), $allowedRoutes)
        ) {
            return redirect()->route('email.verification');
        }

        return $next($request);
    }
}
