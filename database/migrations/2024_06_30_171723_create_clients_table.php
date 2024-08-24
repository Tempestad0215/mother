<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code',30);
            $table->string('name',75);
            $table->string('phone',20);
            $table->string('personal_id',50);
            $table->string('email',150)->nullable()->unique();
            $table->string('address',120)->nullable();
            $table->boolean('status')->default(false);

            $table->enum('type',[1,2,3])->default(1);

            //Informacion del credito
            $table->float('credit_limit');
            $table->float('credit_day');
            $table->float('credit_available');
            $table->float('credit_consumed');
            $table->float('credit_expired');


            //Informacion del anticipo
            $table->float('advance_amount');
            $table->float('advance_date');
            $table->date('advance_expire')->nullable();
            $table->flaot('advance_consumed');
            $table->float('advance_available');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
