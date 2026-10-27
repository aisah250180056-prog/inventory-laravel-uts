<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort = in_array($request->get('sort'), ['name', 'sku', 'purchase_price', 'selling_price', 'stock', 'created_at'])
            ? $request->get('sort')
            : 'name';
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        $products = Product::with(['category', 'supplier'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->get('stock_status') === 'low', function ($query) {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sort', 'direction'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat menambah produk.');

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat menambah produk.');

        $data = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data['price'] = $data['selling_price'];

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier', 'stockMovements' => function ($q) {
            $q->latest()->limit(5);
        }]);

        return view('products.show', compact('product'));
    }

    public function edit(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat mengedit produk.');

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat mengedit produk.');

        $data = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data['price'] = $data['selling_price'];

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
                @unlink(public_path('uploads/products/' . $product->image));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Hanya Admin yang dapat menghapus produk.');

        if ($product->hasTransactions()) {
            return back()->withErrors([
                'error' => 'Produk tidak dapat dihapus karena memiliki riwayat transaksi (pembelian, penjualan, atau mutasi stok).'
            ]);
        }

        if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {
            @unlink(public_path('uploads/products/' . $product->image));
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
