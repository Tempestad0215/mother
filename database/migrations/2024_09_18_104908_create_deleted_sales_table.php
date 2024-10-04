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
            $table->float('discount_amount',4)->default(0);
            $table->float('tax',4);
            $table->float('sub_total',4);
            $table->float('amount',4);
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_sales');
    }
};
