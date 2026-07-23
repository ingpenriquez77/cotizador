<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->onDelete('cascade');

            // Se vincula al producto, pero es nullable por si se borra el producto del catálogo
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // Datos snapshot del ítem (por si cambia en el catálogo, la cotización no se altera)
            $table->string('concept');                  // Nombre o descripción
            $table->integer('quantity')->default(1);
            $table->decimal('cost_price', 10, 2);        // Costo base capturado
            $table->decimal('margin_percentage', 5, 2); // % de ganancia (ej: 30.00, 15.00, 0.00)
            $table->decimal('unit_price', 10, 2);       // Precio de venta calculado
            $table->decimal('subtotal', 10, 2);         // (unit_price * quantity)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
};
