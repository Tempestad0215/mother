<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cos', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('account_co_id');
            $table->string('description');
            $table->decimal('amount', 20);
            $table->enum('type',['sale','payment','discount','refund']);
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_cos');
    }
};
