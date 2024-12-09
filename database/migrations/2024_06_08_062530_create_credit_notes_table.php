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
            $table->string('ncf_m',30)->nullable();
            $table->decimal('n_available',15,4);
            $table->string('type',20)->default('Devolucion');
            $table->boolean('status')->default(true);
            $table->foreignUuid(Sale::class);
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
