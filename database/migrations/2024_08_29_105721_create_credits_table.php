<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Client;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid(Client::class);
            $table->decimal('limit_amount',14,4);
            $table->integer('limit_day');
            $table->integer('expired_day');
            $table->decimal('available',14,4);
            $table->decimal('consumed',14,4);
            $table->decimal('expired_amount',14,4);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
