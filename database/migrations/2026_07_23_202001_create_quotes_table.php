<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Esta tabla vive en la BD SQL (Aiven).
     */
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique(); // Ej: COT-2026-001
            
            // Referencia al ID de MongoDB (ObjectID en formato String)
            $table->string('client_id')->index(); 

            // Financieros
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00); // IVA
            $table->decimal('total', 10, 2)->default(0.00);

            // Estado y control
            $table->enum('status', ['borrador', 'enviada', 'aceptada', 'rechazada'])->default('borrador');
            $table->date('issue_date');
            $table->date('valid_until')->nullable(); // Validez de la cotización
            $table->text('notes')->nullable();       // Condiciones Comerciales o Notas

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};