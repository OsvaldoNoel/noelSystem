<?php

namespace App\Http\Middleware\Branch;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant; // Importación necesaria

class CheckBranchAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado o es landlord, continuar
        if (!$user || $user->isLandlord()) {
            return $next($request);
        }
        $branchId = $request->route('branch')?->id ?? $request->input('branch_id');

        if ($branchId) {
            // Determinar el tenant principal del usuario
            $mainTenantId = $user->sucursal ? $user->tenant_id : $user->sucursal;

            $validBranch = cache()->remember("user:{$user->id}:valid_branch:{$branchId}", 3600, function () use ($branchId, $mainTenantId) {
                return Tenant::where('id', $branchId)
                    ->where(function ($query) use ($mainTenantId) {
                        $query->where('id', $mainTenantId)
                            ->orWhere('sucursal', $mainTenantId);
                    })->exists();
            });

            if (!$validBranch) {
                abort(403, 'Acceso no autorizado a esta sucursal');
            }
        }

        return $next($next);
    }
}
