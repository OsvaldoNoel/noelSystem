<?php

namespace App\Livewire\Tenant\Config\Users\Roles;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;

class UserRoleAssignment extends Component
{
    public $users = [];
    public $selectedUser = null;
    public $availableRoles = [];
    public $userRoles = [];
    public $search = '';

    /**
     * Método mount - Inicialización del componente
     */
    public function mount()
    {
        $this->loadUsers();
        $this->loadRoles();
    }

    /**
     * Carga la lista de usuarios con sus perfiles y roles
     * Se ejecuta al iniciar y cuando cambia el término de búsqueda
     */
    #[On('loadUsers')]
    public function loadUsers()
    {
        $this->users = User::with(['profile', 'roles'])
            ->where('tenant_id', Auth::user()->tenant_id)
            ->when($this->search, function ($query) {
                $query->whereHas('profile', function ($q) {
                    $q->where(function ($subq) {
                        $subq->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%')
                            ->orWhere('ci', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->get()
            ->map(function ($user) {
                $roles = $user->getRoleNames()->toArray();
                $isOwner = in_array('Propietario', $roles);
                $isAdmin = in_array('Admin', $roles);

                return [
                    'id' => $user->id,
                    'name' => $user->profile->name,
                    'lastname' => $user->profile->lastname,
                    'ci' => $user->profile->ci,
                    'is_owner' => $isOwner,
                    'is_admin' => $isAdmin,
                    'roles' => $roles,
                    'roles_count' => count($roles)
                ];
            });
    }

    /**
     * Carga los roles disponibles excluyendo el de Propietario
     */
    #[On('loadRoles')]
    public function loadRoles()
    {
        $currentUser = User::with('roles')->find(Auth::id());
        $isAdmin = $currentUser->roles->contains('name', 'Admin');
        $isOwner = $currentUser->roles->contains('name', 'Propietario');
        $selectedIsAdmin = $this->selectedUser ? $this->selectedUser->roles->contains('name', 'Admin') : false;

        $this->availableRoles = Role::where(function ($query) {
            $query->whereNull('tenant_id')
                ->orWhere('tenant_id', Auth::user()->tenant_id);
        })
            ->where('name', '!=', 'Propietario')
            ->get()
            ->map(function ($role) use ($currentUser, $isAdmin, $isOwner, $selectedIsAdmin) {
                // Por defecto, se puede asignar el rol
                $canAssign = true;

                // Regla 1: Solo Propietario puede asignar Admin
                if ($role->name === 'Admin' && !$isOwner) {
                    $canAssign = false;
                }
                // Regla 2: Si es Admin (no Propietario) y está editando a otro Admin o a sí mismo
                elseif (
                    $isAdmin && !$isOwner && $this->selectedUser &&
                    ($selectedIsAdmin || $currentUser->id == $this->selectedUser->id)
                ) {
                    $canAssign = false;
                }

                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'is_predefined' => in_array($role->name, ['Admin', 'Ventas', 'Compras', 'Caja']),
                    'can_assign' => $canAssign
                ];
            })->toArray();
    }

    /**
     * Determina si un checkbox de rol debe estar deshabilitado
     */
    protected function shouldDisableCheckbox($role, $selectedUser)
    {
        if (!$selectedUser) return true;

        $currentUser = User::with('roles')->find(Auth::id());
        $isAdmin = $currentUser->roles->contains('name', 'Admin');
        $isOwner = $currentUser->roles->contains('name', 'Propietario');
        $selectedIsAdmin = $selectedUser->roles->contains('name', 'Admin');

        // Roles que siempre están deshabilitados
        if ($role['name'] === 'Propietario' || $selectedUser->hasRole('Propietario')) {
            return true;
        }

        // Restricciones para el rol Admin
        if ($role['name'] === 'Admin') {
            return !$isOwner;
        }

        // Restricciones para usuarios Admin
        if ($isAdmin && !$isOwner) {
            return $selectedIsAdmin || ($currentUser->id == $selectedUser->id);
        }

        return false;
    }

    /**
     * Determina si se debe mostrar el candado rojo para un rol específico
     */
    protected function shouldShowLockIcon($role, $selectedUser)
    {
        if (!$selectedUser) return true;

        $currentUser = User::with('roles')->find(Auth::id());
        $isOwner = $currentUser->roles->contains('name', 'Propietario');
        $selectedIsAdmin = $selectedUser->roles->contains('name', 'Admin');

        // Caso especial: No mostrar candado para roles cuando Admin ve a otro Admin
        if ($currentUser->hasRole('Admin') && !$isOwner && $selectedIsAdmin) {
            return false;
        }

        return $this->shouldDisableCheckbox($role, $selectedUser);
    }



    /**
     * Determina si un rol puede ser asignado por el usuario actual
     */
    protected function canAssignRole($roleName, $currentUser)
    {
        // Solo el Propietario puede asignar el rol Admin
        if ($roleName === 'Admin') {
            return $currentUser->hasRole('Propietario');
        }

        return true;
    }

    /**
     * Selecciona un usuario y carga sus roles
     */
    public function selectUser($userId)
    {
        $this->selectedUser = User::with(['roles' => function ($query) {
            $query->wherePivot('tenant_id', Auth::user()->tenant_id);
        }])->find($userId);

        if ($this->selectedUser->hasRole('Propietario')) {
            $this->userRoles = ['Propietario'];
        } else {
            $this->userRoles = $this->selectedUser->getRoleNames()->toArray();
        }
    }

    /**
     * Actualiza los roles del usuario seleccionado
     */
    /**
     * Actualiza los roles del usuario seleccionado
     */
    public function updateUserRoles($roleName, $isChecked)
    {
        if (!$this->selectedUser) {
            $this->dispatch('notify', text: 'Debes seleccionar un usuario primero', bg: 'danger');
            return;
        }

        $currentUser = User::with('roles')->find(Auth::id());
        $selectedUser = User::with('roles')->find($this->selectedUser->id);

        $isAdmin = $currentUser->roles->contains('name', 'Admin');
        $isOwner = $currentUser->roles->contains('name', 'Propietario');
        $selectedIsAdmin = $selectedUser->roles->contains('name', 'Admin');

        // 1. Validación: No se pueden modificar roles del Propietario
        if ($selectedUser->hasRole('Propietario')) {
            $this->dispatch('notify', text: 'No puedes modificar los roles del Propietario', bg: 'danger');
            return;
        }

        // 2. Validación: Restricciones para Admins
        if ($isAdmin && !$isOwner) {
            // Admin no puede modificar otros Admins ni a sí mismo
            if ($selectedIsAdmin || $currentUser->id == $selectedUser->id) {
                $this->dispatch('notify', text: 'No tienes permisos para modificar estos roles', bg: 'danger');
                return;
            }
        }

        // 3. Validación: Solo Propietario puede asignar Admin
        if ($roleName === 'Admin' && $isChecked && !$isOwner) {
            $this->dispatch('notify', text: 'Solo el Propietario puede asignar el rol Admin', bg: 'danger');
            return;
        }

        // Copia temporal para comparación
        $previousRoles = $this->userRoles;

        // Lógica de gestión de roles (lo que ya funcionaba correctamente)
        if ($roleName === 'Admin') {
            if ($isChecked) {
                // Caso 1: Marcando Admin - desmarcar otros roles
                $this->userRoles = ['Admin'];
            }
        } else {
            if ($isChecked) {
                // Caso 2: Marcando otro rol - desmarcar Admin si existe
                $this->userRoles = array_values(array_filter($this->userRoles, fn($r) => $r !== 'Admin'));
                $this->userRoles[] = $roleName;
            } else {
                // Caso 3: Desmarcando otro rol
                $this->userRoles = array_values(array_filter($this->userRoles, fn($r) => $r !== $roleName));
            }
        }

        // Sincronizar roles en la base de datos
        $this->syncRolesToDatabase($selectedUser);

        // Actualizar vista
        $this->refreshComponent($selectedUser);
    }

    protected function syncRolesToDatabase($user)
    {
        $validRoleIds = Role::whereIn('name', $this->userRoles)
            ->where(function ($query) {
                $query->whereNull('tenant_id')
                    ->orWhere('tenant_id', Auth::user()->tenant_id);
            })
            ->pluck('id')
            ->toArray();

        $user->roles()->syncWithPivotValues(
            $validRoleIds,
            ['tenant_id' => Auth::user()->tenant_id]
        );

        // Actualizar sucursal si se quitó Admin
        if (!in_array('Admin', $this->userRoles) && $user->sucursal) {
            $user->update(['sucursal' => null]);
        }
    }

    protected function refreshComponent($user)
    {
        $this->clearPermissionsCache($user);
        $this->selectedUser = $user->fresh(['roles']);
        $this->loadUsers();
        $this->dispatch('loadBranches');

        // Forzar nueva carga de roles para los checkboxes
        $this->userRoles = $this->selectedUser->getRoleNames()->toArray();

        $this->dispatch('notify', text: 'Roles actualizados', bg: 'success');
    }



    /**
     * Limpia la caché de permisos del usuario
     */
    protected function clearPermissionsCache($user)
    {
        $user->forgetCachedPermissions();
        cache()->forget("user.{$user->id}.permissions.tenant." . Auth::user()->tenant_id);

        // Opcional: limpieza adicional para Redis
        Cache::tags(["user_{$user->id}"])->flush();
    }

    /**
     * Recarga los datos del usuario y roles
     */
    protected function refreshData()
    {
        $this->selectedUser->load(['roles' => function ($query) {
            $query->wherePivot('tenant_id', Auth::user()->tenant_id);
        }]);

        $this->loadUsers();
        $this->dispatch('loadBranches');
    }

    /**
     * Actualiza la búsqueda cuando cambia el término
     */
    public function updatedSearch()
    {
        $this->loadUsers();
    }

    /**
     * Renderiza la vista
     */
    public function render()
    {
        $currentUser = User::with('roles')->find(Auth::id());
        $isOwner = $currentUser->roles->contains('name', 'Propietario');

        return view('livewire.tenant.config.users.roles.user-role-assignment', [
            'isOwner' => $isOwner
        ]);
    }
}
