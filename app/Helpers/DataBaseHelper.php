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
        $table->id();
        $table->string('code',30)->unique();
        $table->string('ncf',30)->nullable()->unique();
        $table->string('invoice_type',30)->nullable();
        $table->string('client_name')->nullable()->default('');
        $table->string('client_rnc',30)->nullable();
        $table->foreignIdFor(Client::class,'client_id')
            ->nullable()
            ->constrained('clients')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        $table->float('discount_amount')->default(0);
        $table->float('tax',4);
        $table->float('sub_total',4);
        $table->float('amount',4);


        $table->softDeletes();
        $table->timestamps();
    }
}
