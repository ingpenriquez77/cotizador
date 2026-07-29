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
        // 1. Colección de usuarios
        Schema::connection('mongodb')->create('users', function (Blueprint $collection) {
            // Índice único para evitar correos duplicados
            $collection->unique('email');
            
            // Índice para acelerar búsquedas o filtros por rol (admin / viewer)
            $collection->index('role');
        });

        // 2. Colección de tokens para restablecer contraseña
        Schema::connection('mongodb')->create('password_reset_tokens', function (Blueprint $collection) {
            $collection->index('email');
            $collection->index('token');
        });

        // 3. Colección de sesiones de usuario
        Schema::connection('mongodb')->create('sessions', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('users');
        Schema::connection('mongodb')->dropIfExists('password_reset_tokens');
        Schema::connection('mongodb')->dropIfExists('sessions');
    }
};