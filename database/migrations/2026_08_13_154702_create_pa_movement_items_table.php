<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pa_movement_items', function (Blueprint $table) {
            $table->uuid()->primary()->unique();
            $table->string('code',30)->unique();
            $table->foreignUuid('product_uuid')->constrained('products','uuid')->cascadeOnDelete();
            $table->foreignUuid('tax_uuid')->constrained('taxes','uuid')->cascadeOnDelete();
            $table->foreignUuid('warehouse_uuid')->constrained('warehouses','uuid')->cascadeOnDelete();
            $table->decimal('cost', 19,4);
            $table->decimal('quantity',19,4);
            $table->decimal('tax',19,4);
            $table->decimal('total',19,4);

            $table->index('code');
            $table->index('product_uuid');
            $table->index('tax_uuid');
            $table->index('warehouse_uuid');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pa_movement_items');
    }
};
