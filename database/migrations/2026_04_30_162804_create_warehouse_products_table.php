<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->foreignUuid('product_uuid');
            $table->foreignUuid('warehouse_uuid');
            $table->decimal('stock_quantity',19,4);
            $table->decimal('committed',19,4)->default(0)->nullable();
            $table->decimal('min_stock',19,4)->nullable();
            $table->decimal('max_stock',19,4)->nullable();
            $table->decimal('reorder_level',19,4)->nullable();
            $table->timestamp('last_counted_at')->nullable();
            $table->decimal('purchase_pending',19,4)->default(0)->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_uuid','warehouse_uuid']);
            $table->unique(['product_uuid','warehouse_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_products');
    }
};
