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
            $table->id();
            $table->string('code', 30)->unique();
            $table->foreignIdFor(Client::class, 'client_id');
            $table->float('limit_amount',4);
            $table->integer('limit_day');
            $table->integer('expired_day');
            $table->float('available',4);
            $table->float('consumed',4);
            $table->float('expired_amount');
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
