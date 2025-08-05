<?php

namespace App\Livewire\Tenant\Config\Users\Roles;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth; 
use Livewire\Attributes\On;

class UserRoleAssignment extends Component
{
    public $users = [];
    public $selectedUser = null;
    public $availableRoles = [];
    public $userRoles = [];
    public $search = '';

    public function mount()
    {
        $this->loadUsers();
        $this->loadRoles();
    }

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
                return [
                    'id' => $user->id,
                    'name' => $user->profile->name,
                    'lastname' => $user->profile->lastname,
                    'ci' => $user->profile->ci,
                    'is_owner' => $user->hasRole('Propietario'),
                    'roles' => $user->roles->pluck('name')->toArray()
                ];
            });
    }

    #[On('loadRoles')]
    public function loadRoles()
    {
        $this->availableRoles = Role::where(function ($query) {
            $query->whereNull('tenant_id') // Roles globales
                ->orWhere('tenant_id', Auth::user()->tenant_id); // Roles del tenant
        })
            ->where('name', '!=', 'Propietario')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'is_predefined' => in_array($role->name, ['Admin', 'Ventas', 'Compras', 'Caja'])
                ];
            })->toArray();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::with(['roles' => function ($query) {
            $query->wherePivot('tenant_id', Auth::user()->tenant_id);
        }])->find($userId);

        if ($this->selectedUser->hasRole('Propietario')) {
            $this->userRoles = ['Propietario'];
        } else {
            $this->userRoles = $this->selectedUser->roles
                ->pluck('name')
                ->toArray();
        }
    }

    public function updateUserRoles()
    { 
        if (!$this->selectedUser) return;

        if ($this->selectedUser->hasRole('Propietario')) {
            $this->dispatch('notify', text:'No puedes modificar los roles del Propietario', bg:'danger');
            return;
        }

        // Obtener IDs de roles válidos
        $validRoleIds = Role::whereIn('name', $this->userRoles)
            ->where(function ($query) {
                $query->whereNull('tenant_id')
                    ->orWhere('tenant_id', Auth::user()->tenant_id);
            })
            ->pluck('id')
            ->toArray(); 

        $this->selectedUser->roles()->syncWithPivotValues(
            $validRoleIds,
            ['tenant_id' => Auth::user()->tenant_id]
        );

        // Limpiar caché de permisos
        $this->selectedUser->forgetCachedPermissions();
        cache()->forget("user.{$this->selectedUser->id}.permissions.tenant." . Auth::user()->tenant_id);

        // Recargar relaciones
        $this->selectedUser->load(['roles' => function ($query) {
            $query->wherePivot('tenant_id', Auth::user()->tenant_id);
        }]);

        $this->loadUsers();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.tenant.config.users.roles.user-role-assignment');
    }
}
