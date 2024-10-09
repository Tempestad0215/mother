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
        $table->string('ncf_m',30)->nullable()->unique();
        $table->string('invoice_type',30)->nullable();
        $table->string('client_name')->nullable()->default('');
        $table->foreignIdFor(Client::class,'client_id')
            ->nullable()
            ->constrained('clients')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        $table->string('client_rnc',30)->nullable();
        $table->float('discount_amount')->default(0);
        $table->float('tax',4);
        $table->float('sub_total',4);
        $table->float('amount',4);
        $table->float('n_available',4)->default(0);
        $table->float('n_used',4)->default(0);
        $table->enum('type', ['ventas','cotizacion','devolucion']);
        $table->enum('type_payment',['contado','credito','cheque','tarjeta','transferencia','anticipo'])->default('contado');
        $table->float('received',4)->default(0);
        $table->float('returned',4)->default(0);
        $table->boolean('status')->default(true);
        $table->boolean('close_table')->default(false);
        $table->softDeletes();
        $table->timestamps();
    }
}
