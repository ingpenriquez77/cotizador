<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirigir la raíz directamente al login o al dashboard si ya está autenticado
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas protegidas por Autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard Principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Módulo de Clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

});

// Carga las rutas de Autenticación de Breeze (login, logout, etc.)
require __DIR__.'/auth.php';
