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
            $table->string('code',30)->unique()->comment('codigo');
            $table->foreignIdFor(Sale::class,'sale_id')
                ->nullable()->comment('Relacion de ventas');
            $table->foreignIdFor(Product::class,'product_id')->comment('Relacion de producto');
            $table->foreignIdFor(CreditNote::class,'credit_note_id')
                ->nullable()->comment('Relacion de Nota de Credito');
            $table->string('product_name',75)->comment('Nombre del Producto');
            $table->decimal('stock', 19,4)->comment('Cantidad de Inventario');
            $table->decimal('reserved', 19,4)->comment('Cantidad de Reservado');
            $table->decimal('price',19,6)->comment('Precio Unitario');
            $table->decimal('min_price',19,6)->comment('Precio Minimo');
            $table->decimal('special_price',19,6)->comment('Precio Minimo');
            $table->decimal('tax_rate',19,4)->comment('Tax Rate');
            $table->decimal('tax', 19,4)->comment('Impuesto');
            $table->decimal('amount',19,6)->comment('Total de nota de credito');
            $table->decimal('returned',19,4)->default(0)->comment('Inventario Retornado');
            $table->boolean('ride')->default(false);
            $table->decimal('discount',19,6)->default(0)->comment('Descuento %');
            $table->decimal('discount_amount',19,6)->comment('Total Descuento');
            $table->enum('type',['ENTRADA','VENTAS','SALIDA','CANCELACION','AJUSTE','RESERVA','ELIMINADO','DEVOLUCION'])->comment('tipo de transaccion');
            $table->boolean('status')->default(true)->comment('Estado del Item');
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
