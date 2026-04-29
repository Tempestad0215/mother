<?php

use App\Enums\ModelStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->uuid();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->decimal('rate', 5);
            $table->enum('model_status', ModelStatusEnum::values())->default(ModelStatusEnum::Activo);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
