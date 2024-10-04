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
            $table->string('code',30);
            $table->string('name',75);
            $table->string('description',255)->nullable();
            $table->string('sku',75)->nullable();
            $table->string('bar_code',100)->nullable();
            $table->float('weight')->default(0);
            $table->string('dimensions',255)->nullable();
            $table->string('brand',75)->nullable();
            $table->string('unit',20)->nullable();
            $table->float('stock',4)->default(0);
            $table->float('reserved',4)->default(0);
            $table->float('cost',4)->default(0);
            $table->float('price',4)->default(0);

            //Informacion del producto
            $table->float('product_no_tax',4)->default(0);
            $table->float('tax',4)->default(0);
            $table->float('tax_rate',4)->default(0);
            $table->float('benefits',4)->default(0);

            $table->float('discount',4)->default(0);
            $table->float('discount_amount',4)->default(0);


            //Relaciones de los productos
            $table->foreignIdFor(Category::class,'category_id');
            $table->foreignIdFor(Supplier::class, 'supplier_id');
            $table->enum('type',['producto','servicio'])->default('producto');
            $table->boolean('inventoried')->default(true);
            $table->boolean('status')->default(true);
            $table->softDeletes();
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

