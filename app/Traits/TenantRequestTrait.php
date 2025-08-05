<?php

namespace App\Http\Traits;

use App\Models\Tenant;

trait TenantRequestTrait
{
    public function tenant(): ?Tenant
    {
        // Si ya tenemos el tenant en la solicitud, lo devolvemos
        if ($this->has('tenant')) {
            return $this->get('tenant');
        }

        // Obtenemos el tenant_id de la sesión
        $tenantId = $this->session()->get('tenant_id');

        if (!$tenantId) {
            return null;
        }

        // Obtenemos y cacheamos el tenant
        return cache()->remember("tenant_{$tenantId}", now()->addDay(), function() use ($tenantId) {
            return Tenant::find($tenantId);
        });
    }
}