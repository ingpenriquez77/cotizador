<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Endpoint para verificar el estatus del sistema (Health Check)
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();

        return response()->json([
            'status'    => 'OK',
            'app'       => config('app.name'),
            'database'  => 'connected',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status'    => 'ERROR',
            'app'       => config('app.name'),
            'database'  => 'disconnected',
            'message'   => $e->getMessage(),
            'timestamp' => now()->toIso8601String(),
        ], 500);
    }
})->name('health.check');

// Redireccionar la raíz
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por Autenticación
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo de Clientes (CRUD completo)
    Route::resource('clients', ClientController::class);

    // Módulo de Productos (CRUD completo)
    Route::resource('products', ProductController::class);

    // Módulo de Cotizaciones (CRUD completo)
    Route::resource('quotes', QuoteController::class);

});

// Cargar rutas de autenticación
require __DIR__.'/auth.php';
