<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('product_uuid');
            $table->foreignUuid('warehouse_uuid');
            $table->decimal('available');
            $table->decimal('committed_stock')->nullable();
            $table->decimal('min_stock')->nullable();
            $table->decimal('max_stock')->nullable();
            $table->timestamp('last_counted_at')->nullable();
            $table->decimal('reorder_level')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_uuid','warehouse_uuid']);
            $table->unique(['product_uuid, warehouse_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
