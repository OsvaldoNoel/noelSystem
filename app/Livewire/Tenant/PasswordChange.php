<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use App\Rules\SecurePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class PasswordChange extends Component
{
    public $password = '';
    public $password_confirmation = ''; 

    protected $messages = [
        'password.required' => 'La contraseña es obligatoria',
        'password.confirmed' => 'Las contraseñas no coinciden',
    ];

    public function updatePassword()
    {
        $this->validate([
            'password' => ['required', 'confirmed', new SecurePassword],
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'password' => Hash::make($this->password),
            'password_changed_at' => now(),
        ]);

        // Redirigir al siguiente paso o al home
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('email.verification');
        }

        return redirect()->route('app.home');
    }

    public function render()
    {
        return view('livewire.tenant.password-change');
    }
}
