<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Política de autorización para el modelo User
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier modelo User
     *
     * @param User $user
     * @return Response
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo('ver-usuarios')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver usuarios');
    }

    /**
     * Determina si el usuario puede actualizar otro usuario
     *
     * @param User $user
     * @param User $model
     * @return Response
     */
    public function update(User $user, User $model): Response
    {
        // El Propietario no puede ser modificado por otros usuarios
        if ($model->hasRole(User::ROLE_OWNER, $model->tenant_id)) {
            return $user->hasRole(User::ROLE_OWNER, $user->tenant_id)
                ? Response::allow()
                : Response::deny('No puedes modificar al Propietario');
        }

        // Verificar mismo tenant y permiso
        return $user->tenant_id === $model->tenant_id &&
            $user->hasPermissionTo('editar-usuarios')
            ? Response::allow()
            : Response::deny('No tienes permiso para editar este usuario');
    }

    /**
     * Determina si el usuario puede eliminar otro usuario
     *
     * @param User $user
     * @param User $model
     * @return Response
     */
    public function delete(User $user, User $model): Response
    {
        // Nadie puede eliminar al Propietario
        if ($model->hasRole(User::ROLE_OWNER, $model->tenant_id)) {
            return Response::deny('No puedes eliminar al Propietario');
        }

        // Verificar mismo tenant y permiso
        return $user->tenant_id === $model->tenant_id &&
            $user->hasPermissionTo('eliminar-usuarios')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar este usuario');
    }
}
