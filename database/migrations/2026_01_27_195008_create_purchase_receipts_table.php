<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('supplier_uuid');
            $table->foreignUuid('purchase_uuid');
            $table->foreignUuid('account_co_uuid');
            $table->foreignUuid('user_uuid');
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
