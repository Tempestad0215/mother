<?php

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
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->uuid();
            $table->string('code')->nullable();
            $table->uuid('product_uuid')->nullable();
            $table->bigInteger('sale_id')->nullable();
            $table->uuid('credit_note_uuid')->nullable();
            $table->decimal('quantity', 10, 6)->default(0);
            $table->decimal('price', 10, 6)->default(0);
            $table->decimal('cost', 10, 6)->default(0);
            $table->decimal('discount', 10, 6)->default(0);
            $table->decimal('discount_amount', 10, 6)->default(0);
            $table->decimal('tax_rate', 10, 6)->default(0);
            $table->decimal('tax', 10, 6)->default(0);
            $table->decimal('tax_amount', 10, 6)->default(0);
            $table->decimal('min_price', 10, 6)->default(0);
            $table->decimal('promotional_price', 10, 6)->default(0);
            $table->decimal('subtotal', 10, 6)->default(0);
            $table->decimal('amount', 10, 6)->default(0);
            $table->boolean('status')->default(true);
            $table->enum('type', ['sale', 'return', 'reservation', 'cancelled', 'adjustment'])->default('sale');
            $table->decimal('reserved_quantity', 10, 6)->default(0);
            $table->string('product_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->primary('uuid');
            $table->index('product_uuid');
            $table->index('sale_id');
            $table->index('credit_note_uuid');
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_transactions');
    }
};
