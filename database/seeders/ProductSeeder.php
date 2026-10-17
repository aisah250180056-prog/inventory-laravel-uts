<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop',
                'category' => 'Elektronik',
                'price' => 7500000,
                'stock' => 10,
            ],
            [
                'name' => 'Roti',
                'category' => 'Makanan',
                'price' => 15000,
                'stock' => 25,
            ],
            [
                'name' => 'Air Mineral',
                'category' => 'Minuman',
                'price' => 5000,
                'stock' => 50,
            ],
            [
                'name' => 'Sapu',
                'category' => 'Peralatan Rumah Tangga',
                'price' => 20000,
                'stock' => 15,
            ],
        ];

        foreach ($products as $item) {
            $category = Category::where(
                'name',
                $item['category']
            )->firstOrFail();

            Product::firstOrCreate(
                [
                    'name' => $item['name'],
                    'category_id' => $category->id,
                ],
                [
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                ]
            );
        }
    }
}
