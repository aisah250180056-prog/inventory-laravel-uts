
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('purchases')) {
            Schema::create('purchases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_id')
                    ->constrained('suppliers')
                    ->restrictOnDelete();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->string('purchase_number')->unique();
                $table->decimal('total', 14, 2)->default(0);
                $table->string('status')->default('completed');
                $table->timestamp('purchased_at')->useCurrent();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('purchase_items')) {
            Schema::create('purchase_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_id')
                    ->constrained('purchases')
                    ->cascadeOnDelete();
                $table->foreignId('product_id')
                    ->constrained('products')
                    ->restrictOnDelete();
                $table->unsignedInteger('quantity');
                $table->decimal('unit_cost', 12, 2);
                $table->decimal('subtotal', 14, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->string('sale_number')->unique();
                $table->decimal('total', 14, 2)->default(0);
                $table->string('status')->default('completed');
                $table->timestamp('sold_at')->useCurrent();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')
                    ->constrained('sales')
                    ->cascadeOnDelete();
                $table->foreignId('product_id')
                    ->constrained('products')
                    ->restrictOnDelete();
                $table->unsignedInteger('quantity');
                $table->decimal('unit_price', 12, 2);
                $table->decimal('subtotal', 14, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Sengaja tidak menghapus tabel saat rollback,
        // agar data transaksi tidak terhapus secara tidak sengaja.
    }
};
