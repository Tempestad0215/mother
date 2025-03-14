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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('Codigo');
            $table->string('name',75)->comment('Nombre');
            $table->enum('document',['cedula','pasaporte','rnc','otro'])->comment('Documento');
            $table->string('phone',20)->nullable()->comment('Telefono');
            $table->string('personal_id',50)->nullable()->comment('ID Personal');
            $table->string('email',150)->nullable()->unique()->comment('Email');
            $table->string('address',255)->nullable()->comment('Direccion');
            $table->enum('type',['contado','credito','anticipo'])->default('contado')->comment('Tipo');
            $table->enum('type_price',[1,2,3])->comment('Tipo precio');
            $table->boolean('receive_email')->comment('Recibir email');
            $table->boolean('status')->default(true)->comment('Estado');
            $table->text('comment')->nullable()->comment('Comentario');
            $table->softDeletes();
            $table->timestamps();

            //Datos fulltext
            $table->fullText('personal_id');
            $table->fullText('phone');
            $table->fullText('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
