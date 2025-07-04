<?php

use App\Livewire\Landlord\Config\ConfigLandlord;
use App\Livewire\Landlord\HomeLandlord;
use App\Livewire\Tenant\Clientes\ClientesController;
use App\Livewire\Tenant\Compras\ComprasController;
use App\Livewire\Tenant\Config\TesoreriaConfig;
use App\Livewire\Tenant\HomeTenant;
use App\Livewire\Tenant\Proveedors\ProveedorsController;
use App\Livewire\Tenant\Stock\Productos\ProductController;
use App\Livewire\Tenant\Stock\StockAdmin; 
use App\Livewire\Tenant\Config\Users\UsersTenant;
use App\Livewire\Tenant\Finanzas\Aportes\Tabla\AportesTablaController;
use App\Livewire\Tenant\Finanzas\BancoTesoreria\BancoTesoreriaController;
use App\Livewire\Tenant\Finanzas\CajaTesoreria\CajaTesoreriaController;
use App\Livewire\Tenant\Finanzas\FinanzasController; 
use App\Livewire\Tenant\Reportes\Compras\ReporteComprasController;
use App\Livewire\Tenant\Ventas\CarritoController; 
use Illuminate\Support\Facades\Route;
 
Route::prefix('landlord')->group(function () {
    Route::get('/home', HomeLandlord::class)->name('homeLandlord');
    Route::get('/config', ConfigLandlord::class)->name('configLandlord');
});

Route::prefix('app')->group(function () {
    Route::get('/home', HomeTenant::class)->name('homeApp');

    Route::prefix('stock')->group(function () {
        Route::get('/admin', StockAdmin::class)->name('stockAdmin'); 
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

    Route::prefix('config')->group(function () { 
        Route::get('/tesoreria', TesoreriaConfig::class)->name('tesoreriaConfig');
        Route::get('/users', UsersTenant::class)->name('usersTenant');

    }); 

    Route::prefix('reportes')->group(function () { 
        Route::get('/compras', ReporteComprasController::class)->name('reporteCompras'); 

    }); 
});