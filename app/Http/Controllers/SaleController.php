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
    public function index()
    {
        $sales = Sale::with('user')
            ->latest('sold_at')
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('sales.create', compact('products'));
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
                        'items' => "Stok {$product->name} tidak cukup. "
                            . "Stok tersedia: {$product->stock}.",
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

                $product->stock -= $quantity;
                $product->save();

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
            ->with(
                'success',
                'Penjualan berhasil disimpan dan stok telah diperbarui.'
            );
    }
}
