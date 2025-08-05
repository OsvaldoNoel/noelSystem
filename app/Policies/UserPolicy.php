<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;

class UserPolicy
{
    // UserPolicy.php
    public function update(User $user, User $model)
    {
        // El Propietario no puede ser modificado por otros usuarios
        if ($model->hasRole('Propietario', $model->tenant_id)) {
            return $user->hasRole('Propietario', $user->tenant_id);
        }

        return $user->tenant_id === $model->tenant_id &&
            $user->hasPermissionTo('gestionar-usuarios');
    }
}
