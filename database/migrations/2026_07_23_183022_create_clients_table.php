<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');       // Razon Social / Empresa
            $table->string('contact_name');        // Persona de Contacto
            $table->string('email')->nullable();   // Correo
            $table->string('phone');               // Teléfono / WhatsApp
            $table->text('address')->nullable();   // Dirección
            $table->string('rfc')->nullable();     // RFC
            $table->string('status')->default('activo'); // Activo / Inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
