<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Auth;
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

// Ruta raíz
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// Rutas protegidas por Autenticación
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cotizaciones PDF
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');

    // Cotizaciones CRUD
    Route::resource('quotes', QuoteController::class);

    // Módulos protegidos para Administrador
    Route::middleware('admin')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('products', ProductController::class);
    });

});

// IMPORTANTE: Cargar rutas de auth dentro del middleware 'web' para habilitar las sesiones
Route::middleware('web')->group(function () {
    require __DIR__.'/auth.php';
});
