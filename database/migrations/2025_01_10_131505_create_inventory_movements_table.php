<?php

use App\Enums\InventoryMovementTypeEnum;
use App\Models\Product;
use App\Models\Warehouse;
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
            $table->id();
            $table->foreignIdFor(Product::class, 'product_id')->comment('Relacionde  productos');
            $table->enum('type', InventoryMovementTypeEnum::cases())->comment('Tipo de movimiento');
            $table->foreignIdFor(Warehouse::class, 'warehouse_id')->comment('Relacionde  almacenes');
            $table->morphs('movementable');
            $table->unsignedBigInteger('movementable_line_id')->nullable();
            $table->string('movementable_code')->nullable();
            $table->decimal('quantity',15,4)->comment('Cantidad del movimiento');
            $table->decimal('price',15,6)->comment('costo del movimiento unitario');
            $table->decimal('cost',15,6)->comment('costo del movimiento unitario');
            $table->string('description')->nullable()->comment('Descripcion');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
