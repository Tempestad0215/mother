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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();
            $table->string('client_name')->nullable()->default('');
            $table->foreignIdFor(Client::class,'client_id')->nullable();
            $table->json('info');
            $table->float('discount_amount')->default(0);
            $table->float('tax');
            $table->float('sub_total');
            $table->float('amount');
            $table->text('comment')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
