<?php

namespace App\Http\Middleware\Tenant;

use App\Http\Middleware\Tenant\TenantAwareMiddleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar permisos específicos del tenant
 * 
 * Extiende de TenantAwareMiddleware para obtener el tenant_id automáticamente
 */
class CheckTenantPermissions extends TenantAwareMiddleware
{
    /**
     * Maneja la verificación de permisos 
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();
        $tenantId = $this->getTenantId($request);

        // Landlord tiene acceso completo
        if ($user->isLandlord()) {
            return $next($request);
        }

        foreach ($permissions as $permission) {
            if (!$user->hasPermissionTo($permission, $tenantId)) {
                abort(403, "No tienes permiso para: {$permission}");
            }
        }

        return $next($request);
    }
}