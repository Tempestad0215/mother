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
            $table->uuid()->primary();
            $table->string('code',30)->unique();
            $table->date('from');
            $table->date('to');
            $table->decimal('coin_first')->default(0);
            $table->decimal('coin_second')->default(0);
            $table->decimal('coin_third')->default(0);
            $table->decimal('coin_fourth')->default(0);
            $table->decimal('coin_fifth')->default(0);
            $table->decimal('coin_sixth')->default(0);
            $table->decimal('coin_seventh')->default(0);
            $table->decimal('coin_eighth')->default(0);
            $table->decimal('coin_ninth')->default(0);
            $table->decimal('coin_tenth')->default(0);
            $table->decimal('card')->default(0);
            $table->decimal('transfer')->default(0);
            $table->decimal('check')->default(0);
            $table->decimal('other_income')->default(0);
            $table->decimal('expenses')->default(0);
            $table->decimal('cash_withdrawals')->default(0);
            $table->decimal('refund')->default(0);
            $table->decimal('other_expenses')->default(0);
            $table->decimal('opening_balance')->default(0);
            $table->decimal('total_coin')->default(0);
            $table->decimal('total_other_coin')->default(0);
            $table->decimal('total_expenses')->default(0);
            $table->decimal('diff')->default(0);
            $table->decimal('total_neto')->default(0);
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
