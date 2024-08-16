<?php

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pro_trans', function (Blueprint $table) {
            $table->id();
            $table->string('code',30);
            $table->foreignIdFor(Sale::class,'sale_id');
            $table->foreignIdFor(Product::class,'product_id');
            $table->float('stock',2);
            $table->float('tax');
            $table->float('amount');
            $table->float('price',2);
            $table->float('discount',2)->default(0);
            $table->enum('type',[1,2,3,4,5]);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_trans');
    }
};
