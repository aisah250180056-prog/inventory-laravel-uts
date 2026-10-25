<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sembako & Bahan Pokok', 'description' => 'Beras, minyak goreng, gula, tepung, dan kebutuhan pokok sehari-hari'],
            ['name' => 'Makanan Ringan & Camilan', 'description' => 'Roti, biskuit, keripik, kue kering, dan makanan ringan'],
            ['name' => 'Minuman Segar', 'description' => 'Susu, teh, kopi, jus, dan air mineral kemasan'],
            ['name' => 'Peralatan Rumah Tangga', 'description' => 'Perabot kebersihan, sapu, spons, dan peralatan rumah tangga'],
            ['name' => 'Kebersihan & Perawatan', 'description' => 'Deterjen, sabun cuci piring, pembersih lantai, dan sabun mandi'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }
    }
}
