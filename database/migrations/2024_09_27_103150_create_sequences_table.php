<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('type', ['B01','B02','B03','B04','B11','B12','B13','B14','B15','B16','B17']);
            $table->integer('from');
            $table->integer('next');
            $table->integer('to');
            $table->integer('advise');
            $table->string('num_request',20);
            $table->string('num_authorization',20);
            $table->date('date_request');
            $table->date('date_expire')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
