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
            $table->uuid()->primary();
            $table->string('code',30)->unique();
            $table->string('name',75);
            $table->string('description',255)->nullable();
            $table->string('sku',75)->nullable();
            $table->string('bar_code',100)->nullable();
            $table->float('weight')->default(0);
            $table->string('dimensions',255)->nullable();
            $table->string('brand',75)->nullable();
            $table->string('unit',20)->nullable();
            $table->decimal('stock')->default(0);
            $table->decimal('reserved',4)->default(0);

            //Precio y costo
            $table->decimal('cost')->default(0);
            $table->decimal('special_price')->default(0);
            $table->decimal('min_price')->default(0);
            $table->decimal('price')->default(0);

            //Informacion del producto
            $table->decimal('product_no_tax')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('tax_rate')->default(0);
            $table->decimal('benefits')->default(0);

            $table->decimal('discount')->default(0);
            $table->decimal('discount_amount')->default(0);


            //Relaciones de los productos
            $table->foreignUuid('category_id');
            $table->foreignUuid('supplier_id');
            $table->enum('type',['producto','servicio'])->default('producto');
            $table->boolean('inventoried')->default(true);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();



            //Texto de busqueda completa
            $table->fullText('bar_code');
            $table->fullText('sku');
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

