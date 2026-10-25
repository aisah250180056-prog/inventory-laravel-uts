<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sale::with('user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('sale_number', 'like', "%{$request->search}%");
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('sold_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('sold_at', '<=', $request->end_date);
            })
            ->latest('sold_at')
            ->paginate(15)
            ->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('category')->orderBy('name')->get();

        $productsJson = $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'stock' => $p->stock,
            'price' => (float) ($p->selling_price ?: $p->price),
        ])->values();

        return view('sales.create', compact('products', 'productsJson'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sold_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required', 'integer', 'distinct', 'exists:products,id'
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($request, $data) {
            $saleNumber = 'SO-' . now()->format('YmdHis')
                . '-' . Str::upper(Str::random(5));

            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'sale_number' => $saleNumber,
                'total' => 0,
                'status' => 'completed',
                'sold_at' => $data['sold_at'] ?? now(),
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $unitPrice = (float) (
                    $product->selling_price ?: $product->price
                );

                if ($unitPrice <= 0) {
                    throw ValidationException::withMessages([
                        'items' => "Harga jual {$product->name} belum valid.",
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
                    'note' => 'Penjualan ' . $saleNumber,
                ]);

                $total += $subtotal;
            }

            $sale->update([
                'total' => round($total, 2),
            ]);
        });

        return redirect()->route('sales.index')
            ->with('success', 'Penjualan berhasil disimpan dan stok telah diperbarui.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product.category']);

        return view('sales.show', compact('sale'));
    }

    public function cancel(Request $request, Sale $sale)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat membatalkan transaksi.');

        if ($sale->status === 'cancelled') {
            return back()->withErrors(['error' => 'Transaksi penjualan ini sudah berstatus batal.']);
        }

        DB::transaction(function () use ($request, $sale) {
            $sale->load('items.product');

            foreach ($sale->items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $product->increment('stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $item->quantity,
                    'note' => 'Pembatalan Penjualan ' . $sale->sale_number,
                ]);
            }

            $sale->update(['status' => 'cancelled']);
        });

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Transaksi penjualan berhasil dibatalkan dan stok telah dipulihkan.');
    }
}
