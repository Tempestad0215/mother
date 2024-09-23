<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        //Sacar los datos para el calculo
        $stock = fake()->numberBetween(25, 560);
        $cost = fake()->numberBetween(85, 1520);
        $price = $cost + 50;
        $tax = (int)fake()->randomElement([0,16,18]);
        $tax /= 100;


        //Datos para llenar
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(4),
            'stock' => $stock,
            'cost' => $cost,
            'price' => $price,
            'tax_rate' => $tax * 100,
            'tax' => $price - $tax,
            'product_tax' => $cost * (1 + $tax),
            'product_no_tax' =>  $price - ($price * $tax),
            'benefits' => $price - $cost,
            'type' => fake()->randomElement([ProductTypeEnum::PRODUCTO,ProductTypeEnum::SERVICIO]),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),

        ];
    }
}
