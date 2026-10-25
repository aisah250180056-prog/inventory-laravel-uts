<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $stats = [
            'order_count' => Sale::where('user_id', $user->id)->count(),
            'total_spent' => (float) Sale::where('user_id', $user->id)->where('status', 'completed')->sum('total'),
        ];

        // Katalog produk yang masih memiliki stok > 0
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString();

        // Riwayat pesanan saya terbaru
        $recentOrders = Sale::where('user_id', $user->id)
            ->latest('sold_at')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'products', 'recentOrders'));
    }

    public function orderForm()
    {
        // Hanya tampilkan produk yang stoknya tersedia
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'stock' => $p->stock,
            'price' => (float) ($p->selling_price ?: $p->price),
        ])->values();

        return view('customer.order', compact('products', 'productsJson'));
    }

    public function orderStore(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required', 'integer', 'distinct', 'exists:products,id',
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $sale = DB::transaction(function () use ($request, $data) {
            $saleNumber = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'sale_number' => $saleNumber,
                'total' => 0,
                'status' => 'completed',
                'sold_at' => now(),
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Maaf, stok {$product->name} tidak mencukupi (sisa: {$product->stock} unit).",
                    ]);
                }

                $unitPrice = (float) ($product->selling_price ?: $product->price);

                if ($unitPrice <= 0) {
                    throw ValidationException::withMessages([
                        'items' => "Harga produk {$product->name} belum valid.",
                    ]);
                }

                $subtotal = round($quantity * $unitPrice, 2);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'OUT',
                    'quantity' => $quantity,
                    'note' => 'Pesanan Customer ' . $saleNumber,
                ]);

                $total += $subtotal;
            }

            $sale->update(['total' => round($total, 2)]);

            return $sale;
        });

        return redirect()->route('customer.orders.show', $sale->id)
            ->with('success', 'Pesanan Anda berhasil dibuat dan stok telah dialokasikan!');
    }

    public function myOrders(Request $request)
    {
        $orders = Sale::where('user_id', $request->user()->id)
            ->latest('sold_at')
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function showOrder(Request $request, Sale $sale)
    {
        // Pastikan customer hanya bisa melihat pesanannya sendiri (kecuali admin/staff)
        if ($request->user()->isCustomer() && $sale->user_id !== $request->user()->id) {
            abort(403, 'Anda tidak memiliki hak untuk melihat pesanan ini.');
        }

        $sale->load(['user', 'items.product.category']);

        return view('customer.order_detail', compact('sale'));
    }
}
