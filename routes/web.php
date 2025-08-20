<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\Tenant\PasswordChange;
use App\Livewire\Tenant\EmailVerification;
use App\Services\EmailVerificationService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirección raíz
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return redirect()->route(
            ($user instanceof User && $user->isLandlord())
                ? 'landlord.home'
                : 'app.home'
        );
    }
    return redirect()->route('login');
});

// Rutas para invitados
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('validate-ci', [AuthenticatedSessionController::class, 'validateCi'])->name('validate.ci');
});

// Rutas protegidas con verificación escalonada
Route::middleware(['auth'])->group(function () {
    // Cambio de contraseña (primer paso)
    Route::get('password/change', PasswordChange::class)->name('password.change');
    Route::post('password/change', [PasswordChange::class, 'updatePassword'])->name('password.change.update');

    // Verificación de email (segundo paso)
    Route::get('email/verification', EmailVerification::class)->name('email.verification');
    Route::post('email/verification/resend', [EmailVerification::class, 'resendVerification'])->name('verification.resend');

    // Rutas principales (requieren ambos middlewares)
    Route::middleware(['password.changed', 'verified.custom'])->group(function () {
        require_once 'tenant-routes.php';
        require_once 'landlord-routes.php';
    });
});

// Ruta de verificación por token
Route::get('/email/verify/{token}', function ($token) {
    try {
        $service = app(EmailVerificationService::class);
        $user = $service->verifyEmail($token);
        Auth::login($user);

        return redirect()->route('app.home')->with([
            'verified' => true,
            'message' => 'Email verificado correctamente'
        ]);
    } catch (\Exception $e) {
        return redirect()->route('login')->withErrors([
            'verification' => 'Enlace de verificación inválido o expirado.'
        ]);
    }
})->name('verification.verify');

require __DIR__ . '/auth.php';
