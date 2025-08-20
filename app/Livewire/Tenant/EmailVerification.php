<?php

namespace App\Livewire\Tenant;

use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EmailVerification extends Component
{
    public function mount()
    {
        $user = User::find(Auth::id());

        // Enviar email de verificación si no hay token válido
        if (!EmailVerificationToken::validForUser($user->id)->exists()) {
            app(EmailVerificationService::class)->sendVerificationEmail($user);
        }
    }

    public function resendVerification()
    {
        $user = User::find(Auth::id());
        app(EmailVerificationService::class)->sendVerificationEmail($user);

        $this->dispatch('notify', [
            'text' => 'Email de verificación reenviado',
            'bg' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.tenant.email-verification');
    }
}
