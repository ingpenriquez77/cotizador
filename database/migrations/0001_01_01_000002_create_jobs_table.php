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
        // 1. Colección para los trabajos en cola (Jobs)
        Schema::connection('mongodb')->create('jobs', function (Blueprint $collection) {
            $collection->index('queue');
            $collection->index('reserved_at');
        });

        // 2. Colección para procesamiento por lotes (Job Batches)
        Schema::connection('mongodb')->create('job_batches', function (Blueprint $collection) {
            $collection->index('name');
        });

        // 3. Colección para trabajos fallidos (Failed Jobs)
        Schema::connection('mongodb')->create('failed_jobs', function (Blueprint $collection) {
            $collection->unique('uuid');
            $collection->index(['connection', 'queue', 'failed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('jobs');
        Schema::connection('mongodb')->dropIfExists('job_batches');
        Schema::connection('mongodb')->dropIfExists('failed_jobs');
    }
};