<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; 
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar rutas web para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider y todas serán
| asignadas al grupo de middleware "web".
|
*/

// Redirección raíz basada en autenticación
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

// Rutas protegidas que requieren cambio de contraseña
Route::middleware(['auth', 'verified', 'password.changed'])->group(function () {
    require_once 'landlord-routes.php';
    require_once 'tenant-routes.php';
}); 

require __DIR__ . '/auth.php';
