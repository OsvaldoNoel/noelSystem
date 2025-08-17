<?php

use App\Livewire\Tenant\Clientes\ClientesController;
use App\Livewire\Tenant\Compras\ComprasController;
use App\Livewire\Tenant\Config\ConfigController;
use App\Livewire\Tenant\Config\TesoreriaConfig;
use App\Livewire\Tenant\Config\Users\UserProfileEdit;
use App\Livewire\Tenant\Config\Users\UsersTenant;
use App\Livewire\Tenant\Finanzas\Aportes\Tabla\AportesTablaController;
use App\Livewire\Tenant\Finanzas\BancoTesoreria\BancoTesoreriaController;
use App\Livewire\Tenant\Finanzas\CajaTesoreria\CajaTesoreriaController;
use App\Livewire\Tenant\Finanzas\FinanzasController;
use App\Livewire\Tenant\HomeTenant;
use App\Livewire\Tenant\Proveedors\ProveedorsController;
use App\Livewire\Tenant\Reportes\Compras\ReporteComprasController;
use App\Livewire\Tenant\Stock\Productos\ProductController;
use App\Livewire\Tenant\Stock\StockAdmin;
use App\Livewire\Tenant\Ventas\CarritoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Rutas para los tenants (clientes del sistema)
| Todas las rutas aquí requieren autenticación, verificación de email
| y que el usuario sea de tipo tenant
|
*/

Route::prefix('app')
    ->name('app.')
    ->middleware(['user.type:tenant', 'bind.tenant'])
    ->group(function () {
        // Rutas comunes a todos los tenants
        Route::get('/home', HomeTenant::class)->name('home');
        Route::get('/profile', UserProfileEdit::class)->name('profile');

        Route::prefix('stock')->group(function () {
            Route::get('/admin', StockAdmin::class)->name('stockAdmin')->middleware('permission:gestionar-stock');
            Route::get('/productos', ProductController::class)->name('productos');
            Route::get('/compras', ComprasController::class)->name('compras');
        });

        Route::prefix('clientes')->group(function () {
            Route::get('/listado', ClientesController::class)->name('clientes');
        });

        Route::prefix('proveedores')->group(function () {
            Route::get('/listado', ProveedorsController::class)->name('proveedores');
        });

        Route::prefix('ventas')->group(function () {
            Route::get('/carrito', CarritoController::class)->name('carrito');
        });

        Route::prefix('finanzas')->group(function () {
            Route::get('', FinanzasController::class)->name('finanzas');
            Route::get('/aportes', AportesTablaController::class)->name('aportes');
            Route::get('/cajaTesoreria', CajaTesoreriaController::class)->name('cajaTesoreria');
            Route::get('/bancos', BancoTesoreriaController::class)->name('bancos');
        });

        Route::prefix('admin')->group(function () {
            Route::get('', ConfigController::class)->name('configTenant');
            Route::get('/tesoreria', TesoreriaConfig::class)->name('tesoreriaConfig');
            Route::get('/users', UsersTenant::class)->name('usersTenant'); 
        });

        Route::prefix('reportes')->group(function () {
            Route::get('/compras', ReporteComprasController::class)->name('reporteCompras');
        });



        // Rutas específicas por tipo de tenant
        Route::middleware(['tenant.type:1'])->group(function () { //POS

        });

        Route::middleware(['tenant.type:2'])->group(function () { //Servicios 

        });
    });
