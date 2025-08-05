<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BindTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->resolveTenant($request);

        if ($tenant) {
            // Agregar el tenant al request para acceder con $request->tenant()
            $request->merge(['tenant' => $tenant]); 
        }

        return $next($request);
    }

    protected function resolveTenant(Request $request): ?Tenant
    {
        // 1. Verificar si ya está en el request (para API)
        if ($request->has('tenant')) {
            return $request->get('tenant');
        }

        // 2. Obtener de la sesión (para web)
        $tenantId = $request->session()->get('tenant_id');

        if (!$tenantId) {
            return null;
        }

        return Tenant::find($tenantId);
    } 
}