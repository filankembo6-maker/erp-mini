<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'sku' => strtoupper(fake()->bothify('???-###')),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5, 500),
            'stock_quantity' => fake()->numberBetween(0, 200),
            'stock_alert' => 5,
        ];
    }
}