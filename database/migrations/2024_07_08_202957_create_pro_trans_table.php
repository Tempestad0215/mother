<?php

use App\Models\CreditNote;
use App\Models\Sale;
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
        Schema::create('pro_trans', function (Blueprint $table) {
            $table->id();
            $table->string('code',30);
            $table->foreignIdFor(Sale::class,'sale_id')
                ->nullable()
                ->constrained('sales',)
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->foreignIdFor(Product::class,'product_id')
                ->constrained('products')
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->foreignIdFor(CreditNote::class,'credit_note_id')
                ->nullable()
                ->constrained('credit_notes')
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->string('product_name',75);
            $table->decimal('stock',15,4);
            $table->decimal('reserved');
            $table->decimal('price',15,4);
            $table->decimal('tax_rate');
            $table->decimal('tax');
            $table->decimal('amount',15,4);
            $table->boolean('ride')->default(false);
            $table->decimal('discount',4)->default(0);
            $table->decimal('discount_amount');
            $table->enum('type',['entrada','ventas','salida','cancelacion','ajuste','reserva','eliminado','devolucion']);
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
