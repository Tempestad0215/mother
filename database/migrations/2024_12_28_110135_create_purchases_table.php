<?php

use App\Enums\PurchaseStatusEnum;
use App\Models\Supplier;
use App\Models\User;
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
            $table->foreignIdFor(User::class, 'user_id')->comment('Relacion para el usuario');
            $table->date('doc_date');
            $table->decimal('amount', 19,6)->comment('valor total de la compra');
            $table->decimal('tax', 19,6)->comment('impuesto de la compra');
            $table->decimal('sub_total', 19,6)->comment('Sub total de la compra');
            $table->decimal('discount', 19,6)->default(0)->comment('Descuento Global');
            $table->enum('status', PurchaseStatusEnum::cases())->default(PurchaseStatusEnum::Borrador)->comment('Estado de la orden de compra');
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
