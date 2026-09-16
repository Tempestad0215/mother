<?php

namespace Database\Factories;

use App\Models\CreditNote;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CreditNoteFactory extends Factory
{
    protected $model = CreditNote::class;

    public function definition(): array
    {
        return [
            'sale_uuid' => $this->faker->uuid(),
            'ncf_m' => $this->faker->word(),
            'n_available' => $this->faker->randomFloat(),
            'type' => $this->faker->word(),
            'status' => $this->faker->boolean(),
            'comment' => $this->faker->word(),
            'uuid' => $this->faker->uuid(),
            'code' => $this->faker->word(),
            'ncf' => $this->faker->word(),
            'client_rnc' => $this->faker->word(),
            'client_name' => $this->faker->name(),
            'client_uuid' => $this->faker->uuid(),
            'discount_amount' => $this->faker->randomFloat(),
            'tax' => $this->faker->randomFloat(),
            'sub_total' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomFloat(),
            'n_used' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'received' => $this->faker->randomFloat(),
            'returned' => $this->faker->randomFloat(),

            'sale_id' => Sale::factory(),
        ];
    }
}
