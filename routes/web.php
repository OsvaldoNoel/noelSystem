<?php

use App\Http\Controllers\ProfileController; 
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(
            session('user_type') === 'landlord' ? 'homeLandlord' : 'homeApp'
        );
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('homeApp');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('validate-ci', [AuthenticatedSessionController::class, 'validateCi'])->name('validate.ci');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
}); 

Route::middleware(['auth', 'verified'])
    ->group(function () {
		require_once 'theme-routes.php';    
	});

require __DIR__.'/auth.php';
