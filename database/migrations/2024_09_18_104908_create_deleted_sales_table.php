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
            $table->uuid();
            $table->string('code',30)->unique()->comment('Codigo');

            $table->foreignIdFor(TransCo::class,'trans_co_id')->comment('Relacion de transcciones');
            $table->decimal('discount_amount' ,19,6)->default(0)->comment('Total Descuento');
            $table->decimal('tax' ,19,6)->comment('Total Tax');
            $table->decimal('sub_total',19,6)->comment('Sub Total');
            $table->decimal('amount',19,6)->comment('Total General');
            $table->boolean('close_table')->default(false)->comment('Estado del Cuenta');

            $table->string('comment')->nullable()->comment('Comentario');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_sales');
    }
};
