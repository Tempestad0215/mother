<?php

use App\Models\Category;
use App\Models\Supplier;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',75);
            $table->string('description',255)->nullable();
            $table->string('sku',75)->nullable();
            $table->string('bar_code',100)->nullable();
            $table->float('weight')->default(0);
            $table->string('dimensions',255)->nullable();
            $table->string('brand',75)->nullable();
            $table->string('unit',20);
            $table->float('stock')->default(0);
            $table->float('cost')->default(0);
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->float('discount_percent')->default(0);
            $table->float('tax_rate')->default(0);
            $table->foreignIdFor(Category::class,'category_id');
            $table->foreignIdFor(Supplier::class, 'supplier_id');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

