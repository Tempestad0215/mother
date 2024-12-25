<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuidMorphs('accountable');
            $table->enum('type',['payable','receivable']);
            $table->decimal('amount',15);
            $table->decimal('balance',15);
            $table->integer('due_date');
            $table->decimal('late_fee')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
