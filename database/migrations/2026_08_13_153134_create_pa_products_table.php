<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paProduct', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('code',30)->unique();
            $table->enum('pa_type', \App\Enums\PaMovementType::cases());
            $table->foreignUuid('supplier_uuid')->references('uuid')->on('suppliers');
            $table->date('document_date');
            $table->string('comment');
            $table->decimal('total',18,4);
            $table->decimal('tax',19,4);
            $table->decimal('sub_total',19,4);

            $table->index('code');
            $table->index('pa_type');
            $table->index('supplier_uuid');
            $table->index('document_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paProduct');
    }
};
