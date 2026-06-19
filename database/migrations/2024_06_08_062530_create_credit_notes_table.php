<?php

use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DataBaseHelper;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            DataBaseHElper::saleTable($table);
            //Datos solo de nota de credito
            $table->foreignUuid('sale_uuid')->comment('Relacion con la venta');
            $table->string('ncf_m',30)->nullable()->comment('ncf modificado');
            $table->decimal('n_available', 19,6)->comment('cantiad nota de credito disponible');
            $table->string('type',20)->default('DEVOLUCION')->comment('Tipo Devolucion');
            $table->boolean('status')->default(true)->comment('Estado');
            $table->foreignIdFor(Sale::class, 'sale_id')->comment('relacion de ventas');
            $table->string('comment')->nullable()->comment('Comentario');
            //Full text
            $table->fullText('ncf');
            $table->fullText('ncf_m');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
