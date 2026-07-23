<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Nombre del producto o servicio
            $table->string('brand')->nullable();            // Marca (AMD, ASUS, Servicio, etc.)
            $table->text('description')->nullable();        // Especificaciones técnicas
            $table->decimal('cost_price', 10, 2);          // Precio de Costo base
            $table->boolean('has_margin')->default(true);   // Controla si suma el 30% de utilidad o no
            $table->string('supplier_link')->nullable();    // Link al proveedor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
