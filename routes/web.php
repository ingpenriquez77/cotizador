<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Endpoint para verificar el estatus del sistema (Health Check adaptable a MongoDB)
Route::get('/health', function () {
    try {
        // Comando nativo 'ping' para verificar conexión activa con MongoDB
        DB::connection()->command(['ping' => 1]);

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

// Ruta raíz: Redirige directamente al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas protegidas por Autenticación (Acceso para Admin y Viewer)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cotizaciones: PDF, envío de email y CRUD
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::post('quotes/{quote}/send-email', [QuoteController::class, 'sendEmail'])->name('quotes.send-email');
    Route::resource('quotes', QuoteController::class);

    // Módulos protegidos Exclusivos para Administrador
    Route::middleware('admin')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('products', ProductController::class);
    });

});

// Cargar rutas de autenticación de Laravel Breeze / Jetstream
require __DIR__.'/auth.php';