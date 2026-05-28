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
        Schema::create('credit_note_sale', function (Blueprint $table) {
            $table->foreignUuid('credit_note_uuid');
            $table->foreignUuid('sale_uuid');
            $table->decimal('applied_amount', 19, 6)->default(0);
            $table->index(['credit_note_uuid', 'sale_uuid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_sale');
    }
};