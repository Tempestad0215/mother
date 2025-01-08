<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('account_cos', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->string('name');
            $table->enum('type',['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_cos');
    }
};
