<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Purchase::class);
            $table->foreignIdFor(\App\Models\Supplier::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->timestamp('doc_date')->nullable();
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->enum('status',[\App\Enums\PurchaseStatusEnum::Parcial, \App\Enums\PurchaseStatusEnum::Completada]);
            $table->string('comment', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_receipts');
    }
};
