<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AdvanceFactory extends Factory
{

    public function definition(): array
    {
        return [
            'client_id' => $this->faker->randomNumber(),
            'amount' => $this->faker->randomFloat(),
            'date' => $this->faker->word(),
            'expire' => $this->faker->word(),
            'balance' => $this->faker->randomFloat(),
            'consumed' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean(),
            'code' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
