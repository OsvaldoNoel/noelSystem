<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 

class CheckTenantPermissions extends TenantAwareMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = $request->user();
        $tenantId = $this->getTenantId($request);

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
