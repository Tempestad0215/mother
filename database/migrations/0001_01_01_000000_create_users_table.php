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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid();
            $table->string('name',75)->comment('Nombre');
            $table->string('email',150)->unique()->comment('Correo electronico');
            $table->timestamp('email_verified_at')->nullable()->comment('Verifiacion de email');
            $table->string('password',60)->comment('Clave');
            $table->rememberToken()->comment('Token');
            $table->boolean('status')->default(true)->comment('Estado');
            $table->foreignId('current_team_id')->nullable()->comment('ID de Equipo');
            $table->string('profile_photo_path', 2048)->nullable()->comment('Imagen de perfil');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
