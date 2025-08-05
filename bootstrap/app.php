<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middlewares globales (se ejecutan en cada petición)
        $middleware->web([
            \App\Http\Middleware\RefreshUserPermissions::class,
            \App\Http\Middleware\BindTenant::class,
        ]);

        // Middlewares de API (si tienes)
        $middleware->api([
            \App\Http\Middleware\BindTenant::class,
        ]);

        // Middlewares con alias (para usar en rutas)
        $middleware->alias([
            'tenant.type' => \App\Http\Middleware\CheckTenantType::class,
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            'protect.owner' => \App\Http\Middleware\ProtectOwner::class,
            'tenant.role' => \App\Http\Middleware\VerifyTenantRole::class,
            'tenant.permission' => \App\Http\Middleware\CheckTenantPermissions::class,
            'tenant.branch' => \App\Http\Middleware\CheckBranchAccess::class,
        ]);

        // Agrupar middlewares para reutilización
        $middleware->group('tenant', [
            'tenant.permission',
            'tenant.role',
        ]);

        $middleware->group('branch', [
            'tenant.branch',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Personalización de manejo de excepciones
    })->create();



// // Ejemplo en routes/web.php
// Route::middleware(['tenant'])->group(function () {
//     // Rutas que requieren verificación de tenant
// });

// Route::middleware(['tenant.branch'])->group(function () {
//     // Rutas específicas para manejo de sucursales
// });

// // Uso individual
// Route::get('/admin', function () {
//     // ...
// })->middleware('user.type:landlord');
