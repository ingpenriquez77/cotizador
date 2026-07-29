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
        Schema::connection('mongodb')->create('clients', function (Blueprint $collection) {
            // Índices de búsqueda frecuente
            $collection->index('business_name');
            $collection->index('contact_name');
            $collection->index('email');
            $collection->index('rfc');
            $collection->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('clients');
    }
};