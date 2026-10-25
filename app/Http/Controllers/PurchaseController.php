<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('purchase_number', 'like', "%{$request->search}%");
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('purchased_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('purchased_at', '<=', $request->end_date);
            })
            ->latest('purchased_at')
            ->paginate(15)
            ->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'items.*.unit_cost' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        DB::transaction(function () use ($request, $data) {
            $purchaseNumber = 'PO-'
                . now()->format('YmdHis')
                . '-'
                . Str::upper(Str::random(5));

            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $request->user()->id,
                'purchase_number' => $purchaseNumber,
                'total' => 0,
                'status' => 'completed',
                'purchased_at' => $data['purchased_at'] ?? now(),
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $subtotal = round($quantity * $unitCost, 2);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Tambahkan stok produk & update harga beli terbaru
                $product->increment('stock', $quantity);
                $product->purchase_price = $unitCost;
                $product->save();

                // Catat mutasi stok masuk
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $quantity,
                    'note' => 'Pembelian ' . $purchaseNumber,
                ]);

                $total += $subtotal;
            }

            $purchase->update([
                'total' => round($total, 2),
            ]);
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Transaksi pembelian berhasil disimpan dan stok telah bertambah.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.category']);

        return view('purchases.show', compact('purchase'));
    }

    public function cancel(Request $request, Purchase $purchase)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat membatalkan transaksi.');

        if ($purchase->status === 'cancelled') {
            return back()->withErrors(['error' => 'Transaksi pembelian ini sudah berstatus batal.']);
        }

        DB::transaction(function () use ($request, $purchase) {
            $purchase->load('items.product');

            // Periksa terlebih dahulu apakah semua produk memiliki stok cukup untuk dikurangkan kembali
            foreach ($purchase->items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'error' => "Pembatalan gagal: Stok {$product->name} saat ini ({$product->stock}) lebih kecil dari jumlah pembelian ({$item->quantity}) karena sudah terjual.",
                    ]);
                }
            }

            // Kurangi stok dan catat mutasi stok keluar
            foreach ($purchase->items as $item) {
                $product = Product::whereKey($item->product_id)->firstOrFail();
                $product->decrement('stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'OUT',
                    'quantity' => $item->quantity,
                    'note' => 'Pembatalan Pembelian ' . $purchase->purchase_number,
                ]);
            }

            $purchase->update(['status' => 'cancelled']);
        });

        return redirect()->route('purchases.show', $purchase->id)
            ->with('success', 'Transaksi pembelian berhasil dibatalkan dan stok telah dikurangi.');
    }
}
