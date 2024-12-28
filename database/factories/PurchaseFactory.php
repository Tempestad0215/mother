<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'proccess' => $this->faker->word(),
            'supplier_id' => $this->faker->words(),
            'info' => $this->faker->words(),
            'amount' => $this->faker->randomFloat(),
            'tax' => $this->faker->randomFloat(),
            'sub_total' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
