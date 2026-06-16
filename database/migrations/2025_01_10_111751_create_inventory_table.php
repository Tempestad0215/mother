<?php

use App\Enums\InventoryMovementConceptEnum;
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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('product_uuid');
            $table->foreignUuid('warehouse_uuid');
            $table->string('type', 100);
            $table->string('concept', 255);
            $table->decimal('quantity', 19, 4);
            $table->decimal('cost', 19, 6);
            $table->decimal('stock_before',19,4);
            $table->decimal('stock_after',19,4);
            $table->uuidMorphs('inventoryable');

            //
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
