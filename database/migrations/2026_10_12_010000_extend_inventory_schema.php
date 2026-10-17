<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id(); $table->string('name',150); $table->string('phone',30)->nullable();
                $table->string('email',150)->nullable(); $table->text('address')->nullable(); $table->timestamps();
            });
        }
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'description')) $table->text('description')->nullable();
        });
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) $table->string('sku', 50)->nullable()->unique();
            if (!Schema::hasColumn('products', 'supplier_id')) $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
        });
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) $table->string('username', 50)->nullable()->unique();
        });
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id(); $table->foreignId('product_id')->constrained()->restrictOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->enum('type',['IN','OUT']); $table->unsignedInteger('quantity'); $table->string('note',255)->nullable(); $table->timestamps();
                $table->index('created_at');
            });
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('stock_movements'); Schema::dropIfExists('suppliers');
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products','supplier_id')) $table->dropConstrainedForeignId('supplier_id');
            if (Schema::hasColumn('products','sku')) $table->dropColumn('sku');
        });
        Schema::table('categories', function (Blueprint $table) { if (Schema::hasColumn('categories','description')) $table->dropColumn('description'); });
        Schema::table('users', function (Blueprint $table) { if (Schema::hasColumn('users','username')) $table->dropUnique(['username']); $table->dropColumn('username'); });
    }
};
