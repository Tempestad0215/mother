<?php

use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deleted_sales', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->foreignIdFor(Sale::class, 'sale_id');
            $table->json('info');
            $table->float('discount_amount')->default(0);
            $table->float('tax');
            $table->float('sub_total');
            $table->float('amount');
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_sales');
    }
};
