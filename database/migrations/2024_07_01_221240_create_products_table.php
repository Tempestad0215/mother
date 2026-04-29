<?php

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Warehouse;
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
            $table->uuid();
            $table->string('code',30)->unique()->comment('Codigo');
            $table->string('name',75)->comment('nombre');
            $table->string('description',255)->nullable()->comment('descripcion');
            $table->string('location',255)->nullable()->comment('ubicacion');
            $table->string('sku',75)->nullable()->comment('codigo unico');
            $table->string('bar_code',100)->nullable()->comment('codigo de barra');
            $table->float('weight')->default(0)->comment('peso');
            $table->string('dimensions',255)->nullable()->comment('dimensiones');
            //Precio y costo
            $table->decimal('cost', 19,6)->comment('costo');
            $table->decimal('special_price', 19,6)->default(0)->comment('Precio Especial');
            $table->decimal('min_price', 19,6)->default(0)->comment('Precio Minimo');
            $table->decimal('price', 19,6)->comment('precio');
            //Informacion del producto
            $table->decimal('product_no_tax', 19,6)->default(0)->comment('Precio sin Impuesto');
            $table->decimal('tax', 19,6)->default(0)->comment('Impuesto para este Item');
            $table->decimal('tax_rate', 19,6)->default(0)->comment('Tasa de Impuesto');
            $table->decimal('benefits', 19,6)->default(0)->comment('Beneficios del producto');
            $table->decimal('benefits_rate', 19,6)->default(0)->comment('Porcentaje de Margen');

            $table->decimal('discount', 19,6)->default(0)->comment('Porcentaje de descuento');
            $table->decimal('discount_amount', 19,6)->default(0)->comment('Descuento Total');


            //Relaciones de los productos
            $table->foreignIdFor(Category::class, 'category_id')->comment('Categoria Item');
            $table->foreignIdFor(Supplier::class, 'supplier_id')->comment('Suplidor del Item');

            //Infomacion del producto
            $table->boolean('is_service')->default(0)->comment('Tipo de Servicio');
            $table->boolean('inventoried')->default(true)->comment('Maneja Inventario');
            $table->boolean('status')->default(true)->comment('Estado del Item');
            $table->boolean('has_fraction')->default(true)->comment('Se Puede Fraccionar');
            $table->boolean('has_special')->default(true)->comment('Precio Special Activado');
            $table->boolean('has_discount')->default(true)->comment('Aplica para descuento');
            $table->boolean('has_promotion')->default(true)->comment('Aplica para comisiones');
            $table->boolean('has_tax')->default(true)->comment('Aplica para impuestos');

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

