<?php

namespace App\Http\Middleware\Tenant;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantType
{
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $tenant = $request->tenant();

        if (!$tenant) {
            abort(403, 'No se pudo identificar la empresa');
        }

        // Para sucursales, obtener el tipo de la empresa principal
        $tenantType = $tenant->sucursal
            ? Tenant::find($tenant->sucursal)->tenant_type
            : $tenant->tenant_type;

        if (!in_array($tenantType, $types)) {
            abort(403, 'No tiene permiso para acceder a esta funcionalidad');
        }

        return $next($request);
    }
}
