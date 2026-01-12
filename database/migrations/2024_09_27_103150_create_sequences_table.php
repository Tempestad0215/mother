<?php

use App\Enums\NcfTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique()->comment('codigo unido para cada registro');
            $table->enum('type', NcfTypeEnum::cases())->comment('Tipo de registro');
            $table->integer('from')->comment('numero inicial');
            $table->integer('next')->comment('numero siguiente');
            $table->integer('to')->comment('numero final');
            $table->integer('advise')->comment('mensaje de alerta segun la cantidad prestablecida');
            $table->string('num_request',20)->comment('numero de solicitud');
            $table->string('num_authorization',20)->comment('numero de autorizacion');
            $table->date('date_request')->comment('Fecha de solicitud');
            $table->date('date_expire')->nullable()->comment('Fecha de expiracion');
            $table->boolean('status')->default(1)->comment('Estado del Item');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
