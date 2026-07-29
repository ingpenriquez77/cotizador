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
        $db = DB::connection('mongodb')->getMongoDB();
        $buildInfo = $db->command(['buildInfo' => 1])->toArray()[0];
        $serverStatus = $db->command(['serverStatus' => 1])->toArray()[0];

        // Convertimos el tiempo UTC de Mongo al tiempo local configurado en la App
        $mongoTime = isset($serverStatus['localTime'])
            ? \Carbon\Carbon::parse($serverStatus['localTime']->toDateTime())->timezone(config('app.timezone'))->format('Y-m-d H:i:s T')
            : now()->format('Y-m-d H:i:s T');

        return response()->json([
            'servidor' => [
                'nombre_aplicacion' => config('app.name'),
                'version_php'        => PHP_VERSION,
                'version_laravel'    => app()->version(),
                'hora_servidor'      => now()->format('Y-m-d H:i:s T'),
            ],
            'base_de_datos' => [
                'motor'         => 'MongoDB',
                'nombre_bd'     => DB::connection('mongodb')->getDatabaseName(),
                'version_bd'    => $buildInfo['version'] ?? 'Desconocida',
                'hora_fecha_bd' => $mongoTime,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'ERROR',
            'mensaje' => 'Error al conectar con la base de datos',
            'error'   => $e->getMessage(),
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