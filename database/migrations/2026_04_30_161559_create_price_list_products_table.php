<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('price_list_products', function (Blueprint $table) {
            $table->foreignUuid('product_uuid');
            $table->foreignUuid('price_list_uuid');
            $table->decimal('price',19,4);
            $table->decimal('min_price', 19, 4)->nullable(); // Precio mínimo permitido (piso de venta)
            $table->decimal('promotional_price', 19, 4)->nullable(); // Precio especial/promoción
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_uuid','price_list_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_list_products');
    }
};
