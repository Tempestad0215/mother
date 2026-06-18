<?php

use App\Enums\ClientDocumentEnum;
use App\Enums\ClientTypePriceEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid();
            $table->string('code', 30)->unique()->comment('Codigo');
            $table->enum('type_rnc', \App\Enums\NcfTypeEnum::cases())->default(\App\Enums\NcfTypeEnum::CONSUMO)->comment('Tipo de RNC');
            $table->string('name', 75)->comment('Nombre');
            $table->enum('document', ClientDocumentEnum::cases())->comment('Documento');
            $table->string('phone', 20)->nullable()->comment('Telefono');
            $table->string('personal_id', 50)->nullable()->comment('ID Personal');
            $table->string('email', 150)->nullable()->unique()->comment('Email');
            $table->string('address', 255)->nullable()->comment('Direccion');
            $table->enum('type', \App\Enums\ClientTypeEnum::cases())->default('contado')->comment('Tipo');
            $table->enum('type_price', ClientTypePriceEnum::cases())->comment('Tipo precio');
            $table->boolean('receive_email')->default(false)->comment('Recibir email');
            $table->string('comment')->nullable()->comment('Comentario');
            $table->boolean('status')->default(true)->comment('Estado');
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
