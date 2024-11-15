<?php

namespace Database\Factories;

use App\Models\MoneyCounter;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoneyCounterFactory extends Factory
{
    protected $model = MoneyCounter::class;

    public function definition(): array
    {
        return [
            'from' => $this->faker->word(),
            'to' => $this->faker->word(),
            'coin_first' => $this->faker->randomFloat(),
            'coin_second' => $this->faker->randomFloat(),
            'coin_third' => $this->faker->randomFloat(),
            'coin_fourth' => $this->faker->randomFloat(),
            'coin_fifth' => $this->faker->randomFloat(),
            'coin_sixth' => $this->faker->randomFloat(),
            'coin_seventh' => $this->faker->randomFloat(),
            'coin_eighth' => $this->faker->randomFloat(),
            'coin_ninth' => $this->faker->randomFloat(),
            'coin_tenth' => $this->faker->randomFloat(),
            'card' => $this->faker->randomFloat(),
            'transfer' => $this->faker->randomFloat(),
            'check' => $this->faker->randomFloat(),
            'other_income' => $this->faker->randomFloat(),
            'expenses' => $this->faker->randomFloat(),
            'cash_withdrawals' => $this->faker->randomFloat(),
            'refund' => $this->faker->randomFloat(),
            'other_expenses' => $this->faker->randomFloat(),
            'opening_balance' => $this->faker->randomFloat(),
            'total_coin' => $this->faker->randomFloat(),
            'total_other_coin' => $this->faker->randomFloat(),
            'total_expenses' => $this->faker->randomFloat(),
            'diff' => $this->faker->randomFloat(),
            'total_neto' => $this->faker->randomFloat(),
        ];
    }
}
