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
            $table->string('code',30)->unique();
            $table->date('from');
            $table->date('to');
            $table->decimal('coin_first',10)->default(0);
            $table->decimal('coin_second',10)->default(0);
            $table->decimal('coin_third',10)->default(0);
            $table->decimal('coin_fourth',10)->default(0);
            $table->decimal('coin_fifth',10)->default(0);
            $table->decimal('coin_sixth',10)->default(0);
            $table->decimal('coin_seventh',10)->default(0);
            $table->decimal('coin_eighth',10)->default(0);
            $table->decimal('coin_ninth',10)->default(0);
            $table->decimal('coin_tenth',10)->default(0);
            $table->decimal('card',10)->default(0);
            $table->decimal('transfer',10)->default(0);
            $table->decimal('check',10)->default(0);
            $table->decimal('other_income',10)->default(0);
            $table->decimal('expenses',10)->default(0);
            $table->decimal('cash_withdrawals',10)->default(0);
            $table->decimal('refund',10)->default(0);
            $table->decimal('other_expenses',10)->default(0);
            $table->decimal('opening_balance',10)->default(0);
            $table->decimal('total_coin',10)->default(0);
            $table->decimal('total_other_coin',10)->default(0);
            $table->decimal('total_expenses',10)->default(0);
            $table->decimal('diff',10)->default(0);
            $table->decimal('total_neto',10)->default(0);
            $table->boolean('status')->default(true);
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
