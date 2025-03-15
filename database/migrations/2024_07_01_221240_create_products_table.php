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
            $table->id();
            $table->string('code',30)->unique()->comment('Codigo');
            $table->string('name',75)->comment('nombre');
            $table->string('description',255)->nullable()->comment('descripcion');
            $table->string('location',255)->nullable()->comment('ubicacion');
            $table->string('sku',75)->nullable()->comment('codigo unico');
            $table->string('bar_code',100)->nullable()->comment('codigo de barra');
            $table->float('weight')->default(0)->comment('peso');
            $table->string('dimensions',255)->nullable()->comment('dimensiones');
            $table->string('brand',75)->nullable()->comment('Marca');
            $table->string('unit',20)->nullable()->comment('Unidad');
            $table->decimal('stock')->default(0)->comment('Almacen');
            $table->decimal('reserved',4)->default(0)->comment('En Reserva');

            //Precio y costo
            $table->decimal('cost')->comment('costo');
            $table->decimal('special_price')->default(0)->comment('Precio Especial');
            $table->decimal('min_price')->default(0)->comment('Precio Minimo');
            $table->decimal('price')->comment('precio');

            //Informacion del producto
            $table->decimal('product_no_tax')->default(0)->comment('Precio sin Impuesto');
            $table->decimal('tax')->default(0)->comment('Impuesto para este Item');
            $table->decimal('tax_rate')->default(0)->comment('Tasa de Impuesto');
            $table->decimal('benefits')->default(0)->comment('Beneficios del producto');
            $table->decimal('benefits_rate')->default(0)->comment('Porcentaje de Margen');

            $table->decimal('discount')->default(0)->comment('Porcentaje de descuento');
            $table->decimal('discount_amount')->default(0)->comment('Descuento Total');


            //Relaciones de los productos
            $table->foreignIdFor(Category::class, 'category_id')->comment('Categoria Item');
            $table->foreignIdFor(Supplier::class, 'supplier_id')->comment('Suplidor del Item');
            $table->foreignIdFor(Warehouse::class, 'warehouse_id')->comment('Codigo del Warehouse');

            //Infomacion del producto
            $table->enum('type',['producto','servicio'])->default('producto')->comment('Tipo de Servicio');
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

