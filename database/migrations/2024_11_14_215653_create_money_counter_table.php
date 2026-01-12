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
        Schema::create('money_counter', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('Codigo');
            $table->decimal('coin_first', 19,6)->default(0);
            $table->decimal('coin_second' , 19,6)->default(0);
            $table->decimal('coin_third' , 19,6)->default(0);
            $table->decimal('coin_fourth' , 19,6)->default(0);
            $table->decimal('coin_fifth' , 19,6)->default(0);
            $table->decimal('coin_sixth' , 19,6)->default(0);
            $table->decimal('coin_seventh' , 19,6)->default(0);
            $table->decimal('coin_eighth' , 19,6)->default(0);
            $table->decimal('coin_ninth' , 19,6)->default(0);
            $table->decimal('coin_tenth' , 19,6)->default(0);
            $table->decimal('card' , 19,6)->default(0)->comment('tarjetas');
            $table->decimal('transfer' , 19,6)->default(0)->comment('transfer');
            $table->decimal('check' , 19,6)->default(0)->comment('check');
            $table->decimal('other_income', 19,6)->default(0)->comment('otro ingreso');
            $table->decimal('expenses', 19,6)->default(0)->comment('gastos');
            $table->decimal('cash_withdrawals', 19,6)->default(0)->comment('Dinero en Caja');
            $table->decimal('refund', 19,6)->default(0)->comment('Devoluciones');
            $table->decimal('other_expenses', 19,6)->default(0)->comment('otro gastos');
            $table->decimal('opening_balance', 19,6)->default(0)->comment('BAlance inicial');
            $table->decimal('total_coin', 19,6)->default(0)->comment('Total De moneda');
            $table->decimal('total_other_coin', 19,6)->default(0)->comment('Total De Otras moneda');
            $table->decimal('total_expenses', 19,6)->default(0)->comment('Total de Gastos');
            $table->decimal('diff', 19,6)->default(0)->comment('Diferencia');
            $table->decimal('total_neto', 19,6)->default(0)->comment('Total De Neto');
            $table->string('comment')->nullable()->comment('Comentario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_counter');
    }
};
