<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->string('client_name')->nullable()->default('');
            $table->foreignIdFor(Client::class,'client_id')
                ->nullable()
                ->constrained('clients')
                ->onUpdate('restrict')
                ->onDelete('restrict');
            $table->float('discount_amount')->default(0);
            $table->float('tax',4);
            $table->float('sub_total',4);
            $table->float('amount',4);
            $table->enum('type', ['ventas','cotizacion']);
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->softDeletes();
            $table->timestamps();
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
