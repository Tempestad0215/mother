<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
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
            'document' => fake()->randomElement(['cedula','pasaporte','rnc','otro']),
            'phone' => fake()->phoneNumber(),
            'personal_id' => fake()->numerify('###-#######-#'),
            'receive_email' => true,
            'email' => fake()->email(),
            'address' => fake()->address(),
            'type' => fake()->randomElement(['contado']),
            'comment' => fake()->sentence(20),
        ];
    }
}
