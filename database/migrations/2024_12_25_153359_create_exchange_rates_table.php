<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('Codigo');
            $table->json('rate_info')->comment('informacion de la tasa');
            $table->integer('month')->comment('mes');
            $table->integer('year')->comment('anio');
            $table->boolean('status')->default(true)->comment('Estado del Item');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
