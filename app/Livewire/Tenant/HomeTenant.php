<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HomeTenant extends Component
{  
    public function render()
    {
        return view('livewire.tenant.home-tenant');
    }
}