<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->orderBy('name')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat menambah data pemasok.');

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:2000',
        ]);

        Supplier::create($data);

        return back()->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat mengubah data pemasok.');

        $data = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:2000',
        ]);

        $supplier->update($data);

        return back()->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Request $request, Supplier $supplier)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat menghapus pemasok.');

        if ($supplier->products()->exists() || $supplier->purchases()->exists()) {
            return back()->withErrors([
                'supplier' => 'Pemasok tidak dapat dihapus karena masih digunakan pada data produk atau transaksi pembelian.'
            ]);
        }

        $supplier->delete();

        return back()->with('success', 'Pemasok berhasil dihapus.');
    }
}
