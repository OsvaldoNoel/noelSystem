<?php

namespace App\Livewire\Tenant\Config\Users\Sucursal;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\User;

class UserBranchAssignment extends Component
{

    public $user;
    public $selectedMainTenant;
    public $availableBranches = [];
    public $assignedBranches = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->assignedBranches = $user->tenants()->pluck('tenants.id')->toArray();
    }

    public function updatedSelectedMainTenant($value)
    {
        $this->availableBranches = Tenant::where('sucursal', $value)
            ->orWhere('id', $value)
            ->get();
    }

    public function save()
    {
        $this->user->tenants()->sync($this->assignedBranches);

        // Si necesitas marcar una como principal
        if (count($this->assignedBranches) > 0) {
            $this->user->tenants()->updateExistingPivot($this->assignedBranches[0], ['is_main' => true]);
        }

        session()->flash('message', 'Sucursales asignadas correctamente');
    }

    public function render()
    {
        $mainTenants = Tenant::whereNull('sucursal')->get();

        return view('livewire.tenant.config.users.sucursal.user-branch-assignment', [
            'mainTenants' => $mainTenants
        ]);
    } 
}
