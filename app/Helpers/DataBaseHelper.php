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
        $table->uuid();
        $table->string("code", 30)->unique()->comment('codigo');
        $table->string('ncf',30)->nullable()->unique()->comment('ncf');
        $table->string('invoice_type',30)->nullable()->comment('tipo de factura');
        $table->string('client_name')->nullable()->default('')->comment('nombre del cliente');
        $table->string('client_rnc',30)->nullable()->comment('rnc del cliente');
        $table->foreignIdFor(Client::class,'client_id')->nullable()->comment('relacion de cliente');
        $table->decimal('discount_amount')->default(0)->comment('Cantidad en descuento');
        $table->decimal('tax')->comment('Impuesto');
        $table->decimal('sub_total')->comment('Subtotal');
        $table->decimal('amount')->comment('Cantidad Total');

        $table->softDeletes();
        $table->timestamps();
    }
}
