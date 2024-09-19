<?php

namespace Database\Factories;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition(): array
    {
        return [
            'client_id' => $this->faker->randomNumber(),
            'limit_amount' => $this->faker->randomFloat(),
            'limit_day' => $this->faker->randomNumber(),
            'expired_day' => $this->faker->randomNumber(),
            'balance' => $this->faker->randomFloat(),
            'consumed' => $this->faker->randomFloat(),
            'expired_amount' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean(),
            'code' => $this->faker->word(),
            'available' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
