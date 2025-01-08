<?php

use App\Models\Sale;
use App\Models\TransCo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deleted_sales', function (Blueprint $table) {
            $table->id();
            $table->string('code',30)->unique();

            $table->foreignIdFor(TransCo::class,'trans_co_id');
            $table->decimal('discount_amount')->default(0);
            $table->decimal('tax');
            $table->decimal('sub_total');
            $table->decimal('amount');
            $table->boolean('status')->default(true);
            $table->boolean('close_table')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_sales');
    }
};
