<?php

namespace App\Livewire\Tenant\Config\Users\Sucursal;

use Livewire\Component;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class UserBranchAssignment extends Component
{
    public $users = [];
    public $selectedUser = null;
    public $availableBranches = [];
    public $selectedBranch = null;
    public $search = '';

    public function mount()
    {
        // Redirigir si no hay sucursales
        if (!Tenant::where('sucursal', Auth::user()->tenant_id)->exists()) {
            return redirect()->route('tenant.config.users');
        }
        
        $this->loadUsers();
        $this->loadBranches();
    }

    #[On('loadUsers')]
    public function loadUsers()
    {
        $this->users = User::with(['profile', 'tenant', 'roles'])
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
                $isOwner = $user->hasRole('Propietario');
                $isAdmin = $user->hasRole('Admin');

                return [
                    'id' => $user->id,
                    'name' => $user->profile->name,
                    'lastname' => $user->profile->lastname,
                    'ci' => $user->profile->ci,
                    'branch_id' => $user->sucursal,
                    'branch_name' => $user->sucursal ? Tenant::find($user->sucursal)->name : 'Casa Central',
                    'is_owner' => $isOwner,
                    'is_admin' => $isAdmin,
                    'roles' => $user->getRoleNames()->toArray()
                ];
            });
    }

    #[On('loadBranches')]
    public function loadBranches()
    {
        $this->availableBranches = Tenant::where('sucursal', Auth::user()->tenant_id)
            ->orderBy('name')
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'name' => $branch->name
                ];
            })->toArray();

        // Agregar la opción de "Casa Central" al inicio
        array_unshift($this->availableBranches, [
            'id' => null,
            'name' => 'Casa Central'
        ]);
        $this->loadUsers();
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::with(['tenant', 'roles'])->find($userId);

        // Forzar Casa Central para Propietarios y Admins
        if ($this->selectedUser->hasRole(['Propietario', 'Admin'])) {
            $this->selectedBranch = '';
        } else {
            // Convertir NULL a cadena vacía para que funcione con wire:model
            $this->selectedBranch = $this->selectedUser->sucursal ?? '';
        }
    }

    public function updateUserBranch()
    {
        if (!$this->selectedUser) return;

        // No permitir cambios para Propietarios y Admins
        if ($this->selectedUser->hasRole(['Propietario', 'Admin'])) {
            $this->dispatch('notify', text: 'No se puede cambiar la sucursal de este usuario', bg: 'danger');
            return;
        }

        // Validar que la sucursal pertenezca al tenant
        if (
            $this->selectedBranch && $this->selectedBranch !== '' &&
            !Tenant::where('id', $this->selectedBranch)
                ->where('sucursal', Auth::user()->tenant_id)
                ->exists()
        ) {
            $this->dispatch('notify', text: 'Sucursal no válida', bg: 'danger');
            return;
        }

        // Convertir cadena vacía a NULL para la base de datos
        $branchId = $this->selectedBranch === '' ? null : $this->selectedBranch;

        $this->selectedUser->update([
            'sucursal' => $branchId
        ]);

        $this->dispatch('notify', text: 'Sucursal actualizada', bg: 'success');
        $this->loadUsers();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.tenant.config.users.sucursal.user-branch-assignment');
    }
}
