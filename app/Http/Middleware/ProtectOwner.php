<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $targetUser = $request->route('user'); // Asumiendo que la ruta tiene un parámetro 'user'

        // Verificar si el usuario objetivo es Propietario
        if ($targetUser && $targetUser->hasRole('Propietario', $user->tenant_id)) {
            // Solo permitir si el usuario actual también es Propietario
            if (!$user->hasRole('Propietario', $user->tenant_id)) {
                abort(403, 'No puedes modificar al Propietario del tenant');
            }
        }

        return $next($request);
    }
}
