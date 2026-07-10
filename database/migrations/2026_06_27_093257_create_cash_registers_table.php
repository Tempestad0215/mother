<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->string('user_uuid');
            $table->decimal('opening_balance');
            $table->decimal('closing_balance');
            $table->decimal('expected_balance');
            $table->boolean('status');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
