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
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            // Indexamos el campo role para optimizar consultas y middlewares de permisos
            $collection->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            $collection->dropIndex(['role']);
        });
    }
};