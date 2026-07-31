<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Representa _id en MongoDB
            $table->string('name')->unique();
            $table->string('icon')->nullable(); // Ícono Bootstrap (ej. bi-cpu)
            $table->boolean('is_optional')->default(false); // Define si aparece como (Opcional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};