<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class, 'product_id');
            $table->foreignIdFor(\App\Models\Warehouse::class, 'warehouse_id');
            $table->decimal('qty_on_hand',19,4)->default(0);
            $table->decimal('on_order_qty',19,4)->default(0);
            $table->decimal('committed', 19,4)->default(0);
            $table->decimal('avg_cost', 19,6)->nullable();
            $table->decimal('min_stock', 19,4);
            $table->decimal('max_stock', 19,4);

            //Ubicaciones
            $table->string('rack')->nullable();
            $table->string('bin')->nullable();
            $table->string('zone')->nullable();

            $table->date('expire_date')->nullable();
            $table->decimal('system_stock')->default(0);
            $table->decimal('difference')->default(0);
            $table->string('description')->nullable();
            $table->dateTime('last_recalc_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_counts');
    }
};
