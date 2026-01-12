<?php

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
        Schema::table('products', function (Blueprint $table) {
            //
            $table->foreignIdFor(\App\Models\Tax::class, 'tax_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignIdFor(\App\Models\Unit::class, 'unit_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignIdFor(\App\Models\Brand::class, 'branch_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropForeign('tax_id');
            $table->dropForeign('unit_id');
            $table->dropForeign('branch_id');
        });
    }
};
