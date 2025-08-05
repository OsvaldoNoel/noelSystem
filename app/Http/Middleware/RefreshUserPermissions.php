<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RefreshUserPermissions
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Solo limpiar la caché sin cargar relaciones
            Cache::forget("user.".Auth::id().".permissions");
            
            // El próximo acceso a los permisos cargará datos frescos
        }

        return $next($request);
    }
}