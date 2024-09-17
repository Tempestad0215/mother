<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'code' => 'FAC'.$this->faker->numerify('######'),
            'client_name' => $this->faker->name(),
            'client_id' => $this->faker->randomNumber(),
            'info' => $this->faker->words(),
            'discount_amount' => $this->faker->randomFloat(),
            'tax' => $this->faker->randomFloat(),
            'sub_total' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean(),
            'comment' => $this->faker->word(),
            'close_table' => $this->faker->boolean(),
        ];
    }
}
