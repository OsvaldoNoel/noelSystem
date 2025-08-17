<?php

namespace App\Livewire\Tenant\Config\Users;

use Livewire\Component;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class UsersTenant extends Component
{
    public $activeTab = 'pills-listado';
    public $hasBranches = false;

    protected $queryString = ['activeTab'];

    public function mount()
    {
        $this->activeTab = request()->query('activeTab', 'pills-listado');
        
        // Verificar si el tenant actual tiene sucursales
        $this->hasBranches = Tenant::where('sucursal', Auth::user()->tenant_id)->exists();
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.tenant.config.users.users-tenant');
    }
}