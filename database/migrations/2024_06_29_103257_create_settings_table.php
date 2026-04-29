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
        Schema::create('settings', function (Blueprint $table) {

            //Id
            $table->uuid();
            //Datos de la empresa necesarios
            $table->string('name',150)->comment('Nombre de la empresa');
            $table->string('email',150)->unique()->comment('Correo electronico');
            $table->string('phone',30)->nullable()->unique()->comment('Telefono');
            $table->string('address',255)->nullable()->comment('Direccion');
            $table->string('website',255)->nullable()->comment('Sitio web');
            $table->string('company_id',30)->nullable()->comment('ID de empresa');
            $table->boolean('save_cost')->default(true)->comment('Proteger el costo');
            $table->boolean('sequence')->default(true)->comment('Manejar comprobante');
            $table->string('image_path')->nullable();
            //Datos fiscales de las empresa
            $table->date('fiscal_year')->nullable()->comment('Año Fiscal');

            //Estado
            $table->boolean('status')->default(true)->comment('Estado');
            $table->softDeletes();
            //Datos de crecion y actualizacion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
