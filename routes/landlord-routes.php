<?php

use App\Livewire\Landlord\Config\ConfigLandlord;
use App\Livewire\Landlord\HomeLandlord;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landlord Routes
|--------------------------------------------------------------------------
|
| Rutas exclusivas para el landlord (administrador central)
| Todas las rutas aquí requieren autenticación y verificación de email
| Además, el usuario debe ser de tipo landlord
|
*/

Route::prefix('landlord')
    ->name('landlord.')
    ->middleware(['user.type:landlord'])
    ->group(function () {
        // Dashboard
        Route::get('/home', HomeLandlord::class)->name('home');

        // Configuración
        Route::get('/config', ConfigLandlord::class)->name('config');

        // ... otras rutas del landlord
    });
