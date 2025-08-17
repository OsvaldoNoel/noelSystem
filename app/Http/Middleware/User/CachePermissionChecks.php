<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Support\Facades\Cache;

class CachePermissionChecks
{
    public function handle($request, Closure $next, ...$permissions)
    {
        $user = $request->user();
        $tenantId = $user->tenant_id;
        
        foreach ($permissions as $permission) {
            $cacheKey = "user:{$user->id}:check:{$permission}:tenant:{$tenantId}";
            
            $hasPermission = Cache::remember($cacheKey, now()->addHour(), function () use ($user, $permission, $tenantId) {
                return $user->hasPermissionTo($permission, $tenantId);
            });

            if (!$hasPermission) {
                abort(403, "No tienes permiso para: {$permission}");
            }
        }

        return $next($request);
    }
}