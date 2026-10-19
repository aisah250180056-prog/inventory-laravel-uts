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

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->paginate(15);

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

                // Simpan detail pembelian.
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // Tambahkan stok produk.
                $product->stock += $quantity;
                $product->purchase_price = $unitCost;
                $product->save();

                // Catat riwayat stok masuk satu kali.
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $quantity,
                    'note' => 'Pembelian ' . $purchaseNumber,
                ]);

                $total += $subtotal;
            }

            // Simpan total transaksi pembelian.
            $purchase->update([
                'total' => round($total, 2),
            ]);
        });

        return redirect()
            ->route('purchases.index')
            ->with(
                'success',
                'Transaksi pembelian berhasil disimpan dan stok telah ditambahkan.'
            );
    }
}
