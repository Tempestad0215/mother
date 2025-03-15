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
            $table->enum('type', ['ventas','cotizacion'])->comment('Tipo de Ventas');
            $table->enum('type_payment',['CONTADO','CREDITO','CHEQUE','TARJETA','TRANSFERENCIA','ANTICIPO'])->default('CONTADO')->comment('Tipo de Pago');
            $table->decimal('received')->default(0)->comment('Valor Recibido');
            $table->decimal('returned')->default(0)->comment('Valor Devuelto');
            $table->boolean('status')->default(true)->comment('Estado');
            $table->boolean('close_table')->default(false)->comment('Cuentas abierto o cerrada');
            $table->json('credit_notes')->nullable()->comment('Pago por nota de credito');
            $table->float('credit_notes_amount')->default(0)->comment('Monto de todas las notas de creditos');
            $table->string('comment')->nullable()->comment('Comentario');

            //Fulltext;
            $table->fullText('ncf');
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
