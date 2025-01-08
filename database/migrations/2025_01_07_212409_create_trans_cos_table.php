<?php

use App\Models\ACO;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trans_cos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ACO::class,'account_co_id');
            $table->decimal('amount', 20);
            $table->enum('type',['CREDITO','DEBITO']);
            $table->decimal('debit',15)->default(0);
            $table->decimal('credit',15)->default(0);
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
