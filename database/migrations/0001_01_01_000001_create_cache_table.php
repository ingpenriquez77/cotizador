<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Colección para el Cache
        Schema::connection('mongodb')->create('cache', function (Blueprint $collection) {
            // Índice único para la clave del cache
            $collection->unique('key');
            
            // Índice en el tiempo de expiración para agilizar búsquedas/limpieza
            $collection->index('expiration');
        });

        // 2. Colección para Bloqueos de Cache (Cache Locks / Atomic Locks)
        Schema::connection('mongodb')->create('cache_locks', function (Blueprint $collection) {
            $collection->unique('key');
            $collection->index('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('cache');
        Schema::connection('mongodb')->dropIfExists('cache_locks');
    }
};