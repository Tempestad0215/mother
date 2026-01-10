<?php

use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('codigo unido para cada registro');
            $table->foreignIdFor( Supplier::class,'supplier_id')->comment('Relacion con el suplidor de la orden');
            $table->json('info')->comment('information del pedido');
            $table->decimal('amount')->comment('valor total de la compra');
            $table->decimal('tax')->comment('impuesto de la compra');
            $table->decimal('sub_total')->comment('Sub total de la compra');
            $table->enum('process',['EAPR','ENPR','APRO','CANC','CERR'])->comment('Estado de la orden de compra');
            $table->boolean('status')->default(true)->comment('Estado de la orden de compra');
            $table->string('comment')->nullable()->comment('Comentario');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
