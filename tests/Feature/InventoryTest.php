<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;
    private User $customer;
    private Category $category;
    private Supplier $supplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@inventory.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@inventory.test',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->customer = User::create([
            'name' => 'Budi Customer',
            'username' => 'customer',
            'email' => 'customer@inventory.test',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->category = Category::create([
            'name' => 'Elektronik',
            'description' => 'Kategori perangkat elektronik',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'PT Sumber Makmur',
            'phone' => '08123456789',
            'email' => 'supplier@example.com',
            'address' => 'Jakarta',
        ]);
    }

    /** 1. Auth: Guest tidak dapat membuka dashboard */
    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** 1. Auth: Password salah ditolak */
    public function test_invalid_login_rejected(): void
    {
        $response = $this->post('/login', [
            'identity' => 'admin@inventory.test',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('identity');
        $this->assertGuest();
    }

    /** 2. Product: SKU duplikat ditolak & harga negatif ditolak */
    public function test_product_validation_duplicate_sku_and_negative_price(): void
    {
        Product::create([
            'sku' => 'SKU-001',
            'name' => 'Produk A',
            'category_id' => $this->category->id,
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'price' => 75000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Duplicate SKU test
        $response = $this->actingAs($this->admin)->post('/products', [
            'sku' => 'SKU-001',
            'name' => 'Produk B',
            'category_id' => $this->category->id,
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);
        $response->assertSessionHasErrors('sku');

        // Negative price test
        $responseNeg = $this->actingAs($this->admin)->post('/products', [
            'sku' => 'SKU-002',
            'name' => 'Produk Negatif',
            'category_id' => $this->category->id,
            'purchase_price' => -100,
            'selling_price' => 50000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);
        $responseNeg->assertSessionHasErrors('purchase_price');
    }

    /** 3. Purchase: Pembelian sukses menambah stok */
    public function test_purchase_successfully_increases_stock(): void
    {
        $product = Product::create([
            'sku' => 'SKU-PURCHASE',
            'name' => 'Produk Beli',
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'purchase_price' => 40000,
            'selling_price' => 60000,
            'price' => 60000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 15,
                    'unit_cost' => 45000,
                ],
            ],
        ]);

        $response->assertRedirect(route('purchases.index'));
        $this->assertEquals(25, $product->fresh()->stock);
        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $this->supplier->id,
            'total' => 15 * 45000,
            'status' => 'completed',
        ]);
    }

    /** 4. Sale: Penjualan sukses mengurangi stok sesuai kuantitas */
    public function test_sale_successfully_decreases_stock(): void
    {
        $product = Product::create([
            'sku' => 'SKU-SALE',
            'name' => 'Produk Jual',
            'category_id' => $this->category->id,
            'purchase_price' => 20000,
            'selling_price' => 35000,
            'price' => 35000,
            'stock' => 20,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 8,
                ],
            ],
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertEquals(12, $product->fresh()->stock);
        $this->assertDatabaseHas('sales', [
            'total' => 8 * 35000,
            'status' => 'completed',
        ]);
    }

    /** 5. Insufficient Stock: Penjualan dengan stok tidak cukup ditolak */
    public function test_sale_with_insufficient_stock_is_rejected_without_stock_change(): void
    {
        $product = Product::create([
            'sku' => 'SKU-LIMITED',
            'name' => 'Produk Terbatas',
            'category_id' => $this->category->id,
            'purchase_price' => 10000,
            'selling_price' => 20000,
            'price' => 20000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertEquals(5, $product->fresh()->stock);
        $this->assertEquals(0, Sale::count());
    }

    /** 6. Atomicity: Jika 1 item gagal dalam multi-item, seluruh transaksi rollback */
    public function test_atomic_transaction_rollback_when_one_item_fails(): void
    {
        $prodA = Product::create([
            'sku' => 'SKU-A',
            'name' => 'Produk Cukup',
            'category_id' => $this->category->id,
            'selling_price' => 10000,
            'stock' => 10,
            'minimum_stock' => 1,
        ]);

        $prodB = Product::create([
            'sku' => 'SKU-B',
            'name' => 'Produk Kurang',
            'category_id' => $this->category->id,
            'selling_price' => 20000,
            'stock' => 2,
            'minimum_stock' => 1,
        ]);

        $response = $this->actingAs($this->staff)->post('/sales', [
            'items' => [
                [
                    'product_id' => $prodA->id,
                    'quantity' => 5,
                ],
                [
                    'product_id' => $prodB->id,
                    'quantity' => 10,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertEquals(10, $prodA->fresh()->stock);
        $this->assertEquals(2, $prodB->fresh()->stock);
        $this->assertEquals(0, Sale::count());
    }

    /** 7. Authorization: Staff & Customer dibatasi sesuai perannya */
    public function test_staff_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->staff)->get('/users');
        $response->assertStatus(403);
    }

    public function test_staff_cannot_delete_product(): void
    {
        $product = Product::create([
            'sku' => 'SKU-DEL',
            'name' => 'Produk Hapus',
            'category_id' => $this->category->id,
            'selling_price' => 10000,
            'stock' => 5,
            'minimum_stock' => 1,
        ]);

        $response = $this->actingAs($this->staff)->delete("/products/{$product->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_customer_cannot_access_backoffice_purchases_or_suppliers(): void
    {
        $responsePurchase = $this->actingAs($this->customer)->get('/purchases');
        $responsePurchase->assertStatus(403);

        $responseSupplier = $this->actingAs($this->customer)->get('/suppliers');
        $responseSupplier->assertStatus(403);
    }

    /** 8. Customer Features: Customer dapat mendaftar, membuka form order, memesan barang, dan memotong stok */
    public function test_customer_can_open_order_page(): void
    {
        $response = $this->actingAs($this->customer)->get('/customer/order');
        $response->assertStatus(200);
        $response->assertSee('Buat Pesanan Pembelian Barang');
    }

    public function test_customer_can_order_products_and_decrease_stock(): void
    {
        $product = Product::create([
            'sku' => 'CUST-ITEM',
            'name' => 'Barang Pesanan Customer',
            'category_id' => $this->category->id,
            'selling_price' => 50000,
            'stock' => 20,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->customer)->post('/customer/order', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $this->assertEquals(17, $product->fresh()->stock);
        $this->assertDatabaseHas('sales', [
            'user_id' => $this->customer->id,
            'total' => 150000,
            'status' => 'completed',
        ]);
    }

    public function test_customer_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ani Wijaya',
            'username' => 'ani123',
            'email' => 'ani@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'username' => 'ani123',
            'role' => 'customer',
        ]);
    }

    /** 9. Search & Filter */
    public function test_product_search_and_category_filter(): void
    {
        Product::create([
            'sku' => 'SEARCH-01',
            'name' => 'Mousepad Hitam',
            'category_id' => $this->category->id,
            'selling_price' => 25000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        Product::create([
            'sku' => 'SEARCH-02',
            'name' => 'Flashdisk 32GB',
            'category_id' => $this->category->id,
            'selling_price' => 60000,
            'stock' => 8,
            'minimum_stock' => 2,
        ]);

        $response = $this->actingAs($this->staff)->get('/products?search=Mousepad');
        $response->assertStatus(200);
        $response->assertSee('Mousepad Hitam');
        $response->assertDontSee('Flashdisk 32GB');
    }
}
