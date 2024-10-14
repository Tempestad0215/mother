<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DataBaseHelper;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            DataBaseHelper::saleTable($table);

            //Datos solo de la ventas
            $table->enum('type', ['ventas','cotizacion']);
            $table->enum('type_payment',['contado','credito','cheque','tarjeta','transferencia','anticipo'])->default('contado');
            $table->float('received',4)->default(0);
            $table->float('returned',4)->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->json('credit_notes')->nullable();
            $table->float('credit_notes_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
