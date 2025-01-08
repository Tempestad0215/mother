<?php

use App\Models\CreditNote;
use App\Models\Product;
use App\Models\Sale;
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
        Schema::create('pro_trans', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->foreignIdFor(Sale::class,'sale_id')
                ->nullable();
            $table->foreignIdFor(Product::class,'product_id');
            $table->foreignIdFor(CreditNote::class,'credit_note_id')
                ->nullable();
            $table->string('product_name',75);
            $table->decimal('stock');
            $table->decimal('reserved');
            $table->decimal('price');
            $table->decimal('min_price');
            $table->decimal('special_price');
            $table->decimal('tax_rate');
            $table->decimal('tax');
            $table->decimal('amount');
            $table->boolean('ride')->default(false);
            $table->decimal('discount')->default(0);
            $table->decimal('discount_amount');
            $table->enum('type',['ENTRADA','VENTAS','SALIDA','CANCELACION','AJUSTE','RESERVA','ELIMINADO','DEVOLUCION']);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_trans');
    }
};
