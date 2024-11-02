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
        $table->decimal('discount_amount',15,4)->default(0);
        $table->decimal('tax',15,4);
        $table->decimal('sub_total',15,4);
        $table->decimal('amount',15,4);


        $table->softDeletes();
        $table->timestamps();
    }
}
