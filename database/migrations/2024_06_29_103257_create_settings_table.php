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
            $table->id();

            //Datos de la empresa necesarios
            $table->string('name',150);
            $table->string('email',150)->unique();
            $table->string('phone',30)->nullable()->unique();
            $table->string('address',255)->nullable();
            $table->string('logo',255)->nullable();
            $table->string('website',255)->nullable();
            $table->string('company_id',30)->nullable();
            $table->json('tax')->nullable();
            $table->json('unit');
            $table->boolean('save_cost')->default(true);
            //Datos fiscales de las empresa
            $table->date('fiscal_year')->nullable();

            //Estado
            $table->boolean('status')->default(true);

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
