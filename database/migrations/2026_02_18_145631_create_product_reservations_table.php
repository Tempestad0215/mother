<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Sale::class);
            $table->foreignIdFor(Warehouse::class);
            $table->decimal('quantity');
            $table->enum('status', \App\Enums\ProductReservationEnum::cases());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reservations');
    }
};
