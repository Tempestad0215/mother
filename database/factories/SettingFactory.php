<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'company_id' => fake()->numerify('#########'),
            'tax' => [
                ["name" => "EX","amount" => 0],
                ["name" => "ITBIS1","amount" => 16],
                ["name" => "ITBIS2","amount" => 18],
            ],
            'unit' => ['GALON','UNIDAD','LITRO','ONZA'],
        ];
    }
}
