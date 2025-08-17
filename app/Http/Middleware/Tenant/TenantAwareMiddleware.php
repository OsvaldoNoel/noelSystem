<?php

namespace App\Http\Middleware\Tenant;

use Illuminate\Http\Request;

/**
 * Middleware base para operaciones conscientes del tenant
 * 
 * Proporciona métodos comunes para obtener el tenant_id
 * de diferentes fuentes (rutas, parámetros, usuario)
 */
abstract class TenantAwareMiddleware
{
    /**
     * Obtiene el tenant_id de la solicitud actual
     * 
     */
    protected function getTenantId(Request $request): ?int
    {
        // 1. Verificar si viene en ruta
        if ($request->route('tenant')) {
            return $request->route('tenant')->id;
        }

        // 2. Verificar parámetro tenant_id
        if ($request->has('tenant_id')) {
            return (int) $request->input('tenant_id');
        }

        // 3. Usar tenant del usuario autenticado
        return $request->user()?->tenant_id;
    }

    /**
     * Obtiene el modelo Tenant de la solicitud actual
     * 
     */
    protected function getTenant(Request $request): ?\App\Models\Tenant
    {
        return $request->tenant() ?? $request->user()?->tenant;
    }
}
