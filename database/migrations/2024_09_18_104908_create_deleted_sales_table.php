<?php

use App\Models\Sale;
use App\Models\TransCo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deleted_sales', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('Codigo');

            $table->foreignIdFor(TransCo::class,'trans_co_id')->comment('Relacion de transcciones');
            $table->decimal('discount_amount')->default(0)->comment('Total Descuento');
            $table->decimal('tax')->comment('Total Tax');
            $table->decimal('sub_total')->comment('Sub Total');
            $table->decimal('amount')->comment('Total General');
            $table->boolean('status')->default(true)->comment('Estado del Item');
            $table->boolean('close_table')->default(false)->comment('Estado del Cuenta');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_sales');
    }
};
