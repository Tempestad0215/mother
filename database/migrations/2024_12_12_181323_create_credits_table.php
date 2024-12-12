<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuidMorphs('creditable');
            $table->integer('due_date');
            $table->decimal('limit');
            $table->decimal('balance');
            $table->decimal('consumed');
            $table->decimal('late_fee_interest')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
