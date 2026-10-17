
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'purchase_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('purchase_price', 12, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('products', 'selling_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('selling_price', 12, 2)->default(0);
            });
        }

        if (!Schema::hasColumn('products', 'minimum_stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('minimum_stock')->default(0);
            });
        }

        // Isi harga jual dari harga lama hanya jika masih nol.
        DB::table('products')
    ->where('selling_price', 0)
    ->update([
        'selling_price' => DB::raw('price'),
    ]);
    }

    public function down(): void
    {
        // Kolom tidak dihapus saat rollback untuk menjaga data.
    }
};
