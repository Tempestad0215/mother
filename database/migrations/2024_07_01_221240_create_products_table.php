<?php

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
            $table->string('description',255);
            $table->enum('unit',[1,2,3,4,5,6,7,8,9,10]);
            $table->enum('use',[1,2]);
            $table->float('stock')->default(0);
            $table->float('cost')->default(0);
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
