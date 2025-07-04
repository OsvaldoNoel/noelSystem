<?php

namespace App\Livewire\Tenant;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public $value, $bool, $tenant;

    public function mount()
    {
        $this->tenant = Tenant::find(Auth::user()->tenant_id);
        if($this->tenant != null) {
            $this->tenant = Tenant::find(Auth::user()->tenant_id)->name;
        }
        
        $this->menuSidebar();
    }

    public function menuSidebar()
    {
        $this->value = 0; 
        if ($this->value == 1 ? $this->bool = "false" : $this->bool = "true");
    }
}
