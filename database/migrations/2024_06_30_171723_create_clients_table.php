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
            $table->string('code',30);
            $table->string('name',75);
            $table->string('phone',20)->nullable();
            $table->string('personal_id',50)->nullable( );
            $table->string('email',150)->nullable()->unique();
            $table->string('address',255)->nullable();
            $table->boolean('status')->default(false);
            $table->enum('type',[1,2,3])->default(1);

            $table->timestamps();
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
