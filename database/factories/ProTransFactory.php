<?php

namespace Database\Factories;

use App\Models\ProTrans;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProTransFactory extends Factory
{
    protected $model = ProTrans::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'product_id' => $this->faker->randomNumber(),
            'sale_id' => $this->faker->randomNumber(),
            'stock' => $this->faker->randomFloat(),
            'price' => $this->faker->randomFloat(),
            'cost' => $this->faker->randomFloat(),
            'discount' => $this->faker->randomFloat(),
            'discount_amount' => $this->faker->randomFloat(),
            'tax' => $this->faker->randomFloat(),
            'tax_amount' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean(),
            'type' => $this->faker->word(),
            'created_at' => $this->faker->word(),
            'updated_at' => $this->faker->word(),
        ];
    }
}
