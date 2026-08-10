<?php

namespace Database\Factories;

use App\Models\CreditNote;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductTransactionFactory extends Factory
{
    protected $model = ProductTransaction::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'quantity' => $this->faker->randomFloat(),
            'price' => $this->faker->randomFloat(),
            'cost' => $this->faker->randomFloat(),
            'discount' => $this->faker->randomFloat(),
            'discount_amount' => $this->faker->randomFloat(),
            'tax_rate' => $this->faker->randomFloat(),
            'tax' => $this->faker->randomFloat(),
            'tax_amount' => $this->faker->randomFloat(),
            'min_price' => $this->faker->randomFloat(),
            'promotional_price' => $this->faker->randomFloat(),
            'subtotal' => $this->faker->randomFloat(),
            'amount' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean(),
            'type' => $this->faker->word(),
            'reserved_quantity' => $this->faker->randomFloat(),
            'product_name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'sale_id' => $this->faker->randomNumber(),

            'product_uuid' => Product::factory(),
            'sale_uuid' => Sale::factory(),
            'credit_note_uuid' => CreditNote::factory(),
        ];
    }
}
