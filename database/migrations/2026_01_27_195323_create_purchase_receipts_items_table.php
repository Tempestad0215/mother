<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_receipts_items', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(\App\Models\PurchaseReceipts::class);
            $table->foreignIdFor(\App\Models\PurchaseItem::class,'purchase_item_id');
            $table->foreignIdFor(\App\Models\Product::class,'product_id');
            $table->decimal('cost', 15, 2)->default(0);
            $table->decimal('quantity_expected',12)->default(0);
            $table->decimal('quantity_received',12)->default(0);
            $table->foreignIdFor(\App\Models\Tax::class);
            $table->foreignIdFor(\App\Models\Warehouse::class);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_receipts_items');
    }
};
