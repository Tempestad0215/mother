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
            $table->decimal('stock');
            $table->decimal('price');
            $table->decimal('tax_rate');
            $table->foreignIdFor(Tax::class);
            $table->decimal('discount')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('reserved')->nullable();
            $table->decimal('amount');
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
