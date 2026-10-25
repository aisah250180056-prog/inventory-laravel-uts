<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catSembako = Category::where('name', 'Sembako & Bahan Pokok')->first();
        $catMakanan = Category::where('name', 'Makanan Ringan & Camilan')->first();
        $catMinuman = Category::where('name', 'Minuman Segar')->first();
        $catRumahTangga = Category::where('name', 'Peralatan Rumah Tangga')->first();
        $catKebersihan = Category::where('name', 'Kebersihan & Perawatan')->first();

        $sup1 = Supplier::where('name', 'PT Sumber Pangan Nusantara')->first();
        $sup2 = Supplier::where('name', 'CV Berkah Jaya Abadi')->first();
        $sup3 = Supplier::where('name', 'PT Distribusi Harapan Makmur')->first();

        $products = [
            [
                'sku' => 'SBK-001',
                'name' => 'Beras Premium Ramos 5kg',
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catSembako?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 64000,
                'selling_price' => 74000,
                'price' => 74000,
                'stock' => 35,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'SBK-002',
                'name' => 'Minyak Goreng Sawit Pouch 2L',
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catSembako?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 31000,
                'selling_price' => 36500,
                'price' => 36500,
                'stock' => 50,
                'minimum_stock' => 15,
            ],
            [
                'sku' => 'SBK-003',
                'name' => 'Gula Pasir Kristal Putih 1kg',
                'image' => 'https://images.unsplash.com/photo-1581441363689-1f3c3c414635?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catSembako?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 15000,
                'selling_price' => 18000,
                'price' => 18000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'MNM-001',
                'name' => 'Susu UHT Full Cream 1 Liter',
                'image' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMinuman?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 16500,
                'selling_price' => 21000,
                'price' => 21000,
                'stock' => 25,
                'minimum_stock' => 8,
            ],
            [
                'sku' => 'MNM-002',
                'name' => 'Kopi Susu Gula Aren Botol 250ml',
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMinuman?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 9000,
                'selling_price' => 14000,
                'price' => 14000,
                'stock' => 30,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'MNM-003',
                'name' => 'Teh Botol Melati Dingin 350ml',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMinuman?->id,
                'supplier_id' => $sup1?->id,
                'purchase_price' => 3500,
                'selling_price' => 5000,
                'price' => 5000,
                'stock' => 4, // Stok Menipis untuk demo alert!
                'minimum_stock' => 12,
            ],
            [
                'sku' => 'MKN-001',
                'name' => 'Roti Tawar Gandum Kupas',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMakanan?->id,
                'supplier_id' => $sup2?->id,
                'purchase_price' => 14000,
                'selling_price' => 18500,
                'price' => 18500,
                'stock' => 20,
                'minimum_stock' => 6,
            ],
            [
                'sku' => 'MKN-002',
                'name' => 'Biskuit Cokelat Sandwich Box',
                'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMakanan?->id,
                'supplier_id' => $sup2?->id,
                'purchase_price' => 8500,
                'selling_price' => 12000,
                'price' => 12000,
                'stock' => 35,
                'minimum_stock' => 8,
            ],
            [
                'sku' => 'MKN-003',
                'name' => 'Keripik Kentang Renyah Rasa Balado',
                'image' => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catMakanan?->id,
                'supplier_id' => $sup2?->id,
                'purchase_price' => 11000,
                'selling_price' => 15000,
                'price' => 15000,
                'stock' => 3, // Stok Menipis untuk demo alert!
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'PRT-001',
                'name' => 'Sabun Cuci Piring Cair Jeruk Nipis 750ml',
                'image' => 'https://images.unsplash.com/photo-1584813539806-2538b8d9b8c6?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catKebersihan?->id,
                'supplier_id' => $sup3?->id,
                'purchase_price' => 12500,
                'selling_price' => 16500,
                'price' => 16500,
                'stock' => 28,
                'minimum_stock' => 8,
            ],
            [
                'sku' => 'PRT-002',
                'name' => 'Pembersih Lantai Aroma Lavender 800ml',
                'image' => 'https://images.unsplash.com/photo-1585421514284-efb74c2b69ba?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catKebersihan?->id,
                'supplier_id' => $sup3?->id,
                'purchase_price' => 11000,
                'selling_price' => 14500,
                'price' => 14500,
                'stock' => 22,
                'minimum_stock' => 5,
            ],
            [
                'sku' => 'PRT-003',
                'name' => 'Sapu Lantai Ijuk Nylon Microfiber',
                'image' => 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?w=600&auto=format&fit=crop&q=80',
                'category_id' => $catRumahTangga?->id,
                'supplier_id' => $sup2?->id,
                'purchase_price' => 24000,
                'selling_price' => 32000,
                'price' => 32000,
                'stock' => 18,
                'minimum_stock' => 5,
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['sku' => $item['sku']],
                $item
            );
        }
    }
}
