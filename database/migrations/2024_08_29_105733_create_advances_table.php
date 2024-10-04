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
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->foreignIdFor(Client::class,'client_id')->constrained('clients')->onUpdate('restrict')->onDelete('restrict');
            $table->float('amount',4);
            $table->date('date');
            $table->date('expire')->nullable();
            $table->float('balance',4);
            $table->float('consumed',4);
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
        Schema::dropIfExists('advances');
    }
};
