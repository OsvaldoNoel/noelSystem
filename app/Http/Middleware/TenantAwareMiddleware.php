<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

abstract class TenantAwareMiddleware
{
    protected function getTenantId(Request $request)
    {
        // 1. Verificar si viene en ruta
        if ($request->route('tenant')) {
            return $request->route('tenant')->id;
        }
        
        // 2. Verificar parámetro tenant_id
        if ($request->has('tenant_id')) {
            return $request->input('tenant_id');
        }
        
        // 3. Usar tenant del usuario autenticado
        return $request->user()->tenant_id;
    }
}