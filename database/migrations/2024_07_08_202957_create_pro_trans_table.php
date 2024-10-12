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
            $table->float('stock',4);
            $table->float('price',4);
            $table->float('tax_rate',4);
            $table->float('tax',4);
            $table->float('amount',4);
            $table->float('discount',4)->default(0);
            $table->float('discount_amount',4);
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
