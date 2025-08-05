<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTenantRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Solo aplica a usuarios tenant
        if ($user->isTenant()) {
            // Verificar si se está intentando acceder a un rol que no pertenece al tenant
            if ($request->route('role')) {
                $role = $request->route('role');
                if ($role->tenant_id !== $user->tenant_id) {
                    abort(403, 'No tienes permiso para acceder a este rol');
                }
            }
        }

        return $next($request);
    }
}
