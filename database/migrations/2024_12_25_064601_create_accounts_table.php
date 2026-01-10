<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->comment('Codigo');
            $table->morphs('accountable');
            $table->enum('type',['PAYABLE','RECEIVABLE'])->comment('Tipo de cuenta');
            $table->decimal('amount',15)->comment('Total');
            $table->decimal('balance',15)->comment('Saldo');
            $table->integer('due_date')->comment('Fecha vencimiento');
            $table->decimal('late_fee')->default(0)->comment('Interes por pago vencido');
            $table->boolean('status')->default(true)->comment('Estado del Item');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
