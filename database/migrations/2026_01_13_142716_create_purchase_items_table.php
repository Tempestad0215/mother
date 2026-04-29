<?php

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('product_uuid');
            $table->foreignUuid('purchase_uuid');
            $table->foreignUuid('tax_uuid');
            $table->foreignUuid('warehouse_uuid');
            $table->decimal('quantity',19,6);
            $table->decimal('cost',19,6);
            $table->decimal('discount',19,6)->default(0);
            $table->decimal('amount',19,6);
            $table->decimal('tax',19,6);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
