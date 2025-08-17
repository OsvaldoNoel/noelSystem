<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use App\Rules\SecurePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class HomeTenant extends Component
{
    public $password = '';
    public $password_confirmation = '';

    public function render()
    {
        $user = User::find(Auth::id());

        if ($user->needsPasswordChange()) {
            return view('livewire.tenant.password-change');
        }

        return view('livewire.tenant.home-tenant');
    }

    public function updatePassword()
    {
        $user = User::find(Auth::id());

        $this->validate([
            'password' => ['required', 'confirmed', new SecurePassword],
        ]);

        $user->update([
            'password' => Hash::make($this->password),
            'password_changed_at' => now(),
        ]);

        // Forzar recarga para actualizar el estado de needsPasswordChange()
        return redirect()->route('app.home');
    }
}
