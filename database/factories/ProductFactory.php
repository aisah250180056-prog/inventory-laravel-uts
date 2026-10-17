<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'category_id' => Category::query()->inRandomOrder()->value('id')
                ?? Category::factory(),
            'price' => fake()->numberBetween(1000, 1000000),
            'stock' => fake()->numberBetween(0, 100),
        ];
    }
}
