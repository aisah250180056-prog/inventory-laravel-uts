<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Sumber Pangan Nusantara',
                'phone' => '081234567890',
                'email' => 'order@sumberpangan.test',
                'address' => 'Kawasan Industri Pulogadung No. 18, Jakarta Timur',
            ],
            [
                'name' => 'CV Berkah Jaya Abadi',
                'phone' => '082345678901',
                'email' => 'sales@berkahjaya.test',
                'address' => 'Jl. Soekarno-Hatta No. 120, Bandung',
            ],
            [
                'name' => 'PT Distribusi Harapan Makmur',
                'phone' => '083456789012',
                'email' => 'kontak@distribusiharapan.test',
                'address' => 'Jl. Rungkut Industri No. 45, Surabaya',
            ],
        ];

        foreach ($suppliers as $item) {
            Supplier::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
