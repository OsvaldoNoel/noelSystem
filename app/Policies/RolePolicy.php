<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response; 
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    // RolePolicy.php
    public function viewAny(User $user)
    {
        return $user->isLandlord() || $user->hasPermissionTo('gestionar-roles');
    }

    public function create(User $user)
    {
        return $user->isTenant() && $user->hasPermissionTo('gestionar-roles');
    }

    public function delete(User $user, Role $role)
    {
        // No permitir eliminar roles predefinidos
        if (in_array($role->name, ['Propietario', 'Admin', 'Ventas', 'Compras', 'Caja'])) {
            return false;
        }

        return $user->tenant_id === $role->tenant_id &&
            $user->hasPermissionTo('gestionar-roles');
    }
}
