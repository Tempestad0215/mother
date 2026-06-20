<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->uuid('uuid')->primary(); // Siguiendo tu estándar de UUIDs

            // Relación con la nota de crédito madre (id en tu tabla es uuid)
            $table->uuid('credit_note_uuid');

            // Producto que se está devolviendo/afectando
            $table->uuid('product_uuid');

            // Cantidades usando el mismo tipo numeric(19, 4) de tus almacenes
            $table->decimal('quantity', 19, 4);
            $table->decimal('price', 19, 4); // Precio unitario al que se vendió
            $table->decimal('tax', 19, 4)->default(0); // Impuesto devuelto por unidad
            $table->decimal('sub_total', 19, 4);
            $table->decimal('amount', 19, 4); // Total del ítem (Cantidad * Precio)

            $table->timestamps();
            $table->softDeletes();

            // Llaves foráneas e índices
            // $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');
            // $table->foreign('product_uuid')->references('uuid')->on('products'); // Asumiendo que tu tabla es 'products'

            $table->index(['credit_note_id', 'product_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
    }
};
