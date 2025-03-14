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
            $table->id();
            $table->string('code',30)->unique()->comment('codigo unido para cada registro');
            $table->string('contact',75)->nullable()->comment('Nombre del representante');
            $table->string('company_name',150)->comment('nombre completo');
            $table->enum('type_payment',['CONTADO','CREDITO','CHEQUE','TARJETA','TRANSFERENCIA','ANTICIPO']);
            $table->string('phone',20)->nullable()->comment('Telefono');
            $table->string('email',150)->nullable()->unique()->comment('Correo electronico');
            $table->boolean('receive_email')->default(false)->comment('Email Recibido');
            $table->string('account_bank',30)->nullable()->comment('Cuenta de Banco');
            $table->boolean('is_recurring')->default(false)->comment('Pago Recurrente');
            $table->integer('payment_day')->nullable()->comment('Dia de pago del mes');
            $table->boolean('status')->default(true)->comment('Estado');
            $table->text('comment')->nullable()->comment('Comentario');
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
