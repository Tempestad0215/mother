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
            $table->foreignUuid( 'tax_uuid');
            $table->foreignUuid( 'unit_uuid')
                ->nullable();
            $table->foreignUuid('brand_uuid')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::table('products', function (Blueprint $table) {
//            //
//            $table->dropForeign('tax_id');
//            $table->dropForeign('unit_id');
//            $table->dropForeign('branch_id');
//        });
    }
};
