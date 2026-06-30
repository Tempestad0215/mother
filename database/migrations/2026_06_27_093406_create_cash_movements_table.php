<?php

use App\Enums\CashMovementType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('cash_register_uuid');
            $table->Enum('type', CashMovementType::cases());
            $table->decimal('amount',19,4);
            $table->string('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
