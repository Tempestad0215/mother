<?php

namespace App\Helpers;

use App\Models\Client;
use Illuminate\Database\Schema\Blueprint;

class DataBaseHelper
{
    /**
     * @param Blueprint $table
     * @return void
     */
    public static function saleTable(Blueprint $table):void
    {
        $table->uuid()->primary();
        $table->string('ncf',30)->nullable()->unique();
        $table->string('invoice_type',30)->nullable();
        $table->string('client_name')->nullable()->default('');
        $table->string('client_rnc',30)->nullable();
        $table->foreignUuid('client_id')
            ->nullable();
        $table->decimal('discount_amount')->default(0);
        $table->decimal('tax');
        $table->decimal('sub_total');
        $table->decimal('amount');


        $table->softDeletes();
        $table->timestamps();
    }
}
