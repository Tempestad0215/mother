<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->string('cash_register');
            $table->string('type');
            $table->decimal('amount');
            $table->string('concept');
            $table->string('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
