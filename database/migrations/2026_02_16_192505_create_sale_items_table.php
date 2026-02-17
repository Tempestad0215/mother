<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\Tax;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Sale::class);
            $table->decimal('stock',19,4);
            $table->decimal('price',19,4);
            $table->decimal('tax_rate');
            $table->foreignIdFor(Tax::class);
            $table->decimal('discount',19,4)->nullable();
            $table->decimal('discount_amount',19,4)->nullable();
            $table->decimal('reserved',19,4)->nullable();
            $table->decimal('amount',19,4);
            $table->boolean('is_service');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
