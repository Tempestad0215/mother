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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('contact',75)->nullable();
            $table->string('company_name',150);
            $table->enum('type_payment',['contado','credito','cheque','tarjeta','transferencia','anticipo','otros']);
            $table->string('phone',20)->nullable();
            $table->string('email',150)->nullable()->unique();
            $table->boolean('receive_email')->default(false);
            $table->string('account_bank',30)->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('payment_day')->nullable()->comment('Dia de pago del mes');
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();

            //full text
            $table->fullText('phone');
            $table->fullText('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
