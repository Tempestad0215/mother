<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->foreignUuid('product_id');
            $table->foreignUuid('warehouse_id');
            $table->decimal('stock_quantity',19,4);
            $table->decimal('min_stock',19,4);
            $table->decimal('max_stock',19,4);
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id','warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_products');
    }
};
