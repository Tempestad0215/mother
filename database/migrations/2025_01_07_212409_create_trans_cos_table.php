<?php

use App\Models\ACO;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cos', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('account_co_id')->comment('Relacion de cuenta');
            $table->decimal('amount', 20)->comment('Monto');
            $table->enum('type',['CREDITO','DEBITO'])->comment('Tipo cuenta');
            $table->decimal('debit',15)->default(0)->comment('Monto de debito');
            $table->decimal('credit',15)->default(0)->comment('Monto de credito');
            $table->timestamp('date')->useCurrent()->comment('Fecha y hora');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cos');
    }
};
