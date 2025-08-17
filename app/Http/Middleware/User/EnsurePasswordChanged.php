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

        $allowedRoutes = [
            'logout',
            'app.home', // Permitir siempre el home
            'password.change.update', // Permitir el endpoint de actualización
        ];

        if (
            $user && $user->needsPasswordChange() &&
            !in_array($request->route()->getName(), $allowedRoutes)
        ) {
            return redirect()->route('app.home');
        }

        return $next($request);
    }
}
