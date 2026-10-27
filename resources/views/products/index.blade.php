@extends('layouts.app')

@section('title', 'Daftar Produk - Inventory Management System')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Master Data Produk</h1>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn">+ Tambah Produk</a>
        @endif
    </div>

    <!-- Form Pencarian dan Filter -->
    <form method="GET" action="{{ route('products.index') }}" style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari SKU atau nama produk..."
                    value="{{ request('search') }}"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div style="min-width: 180px;">
                <select name="category_id" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="">-- Semua Kategori --</option>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="min-width: 180px;">
                <select name="stock_status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="">-- Semua Status Stok --</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Menipis (<= Min)</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn">Filter</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div style="overflow-x: auto;">
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Pemasok</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Min. Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr style="{{ $product->isLowStock() ? 'background-color: #fff1f2;' : '' }}">
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td style="text-align: center; padding: 6px;">
                            <img
                                src="{{ $product->image_url }}"
                                alt="{{ $product->name }}"
                                style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;"
                                onerror="this.src='https://placehold.co/100x100/e2e8f0/1e293b?text=Item'"
                            >
                        </td>
                        <td><strong>{{ $product->sku ?? '-' }}</strong></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td>{{ $product->supplier?->name ?? '-' }}</td>
                        <td>Rp {{ number_format((float) ($product->purchase_price ?: 0), 0, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) ($product->selling_price ?: $product->price), 0, ',', '.') }}</td>
                        <td><strong>{{ $product->stock }}</strong></td>
                        <td>{{ $product->minimum_stock }}</td>
                        <td>
                            @if($product->isLowStock())
                                <span style="background-color: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Menipis
                                </span>
                            @else
                                <span style="background-color: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Aman
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('products.show', $product->id) }}" style="font-weight: 500;">Detail</a>

                                @if(auth()->user()->isAdmin())
                                    <span>|</span>
                                    <a href="{{ route('products.edit', $product->id) }}" style="font-weight: 500;">Edit</a>
                                    <span>|</span>
                                    <form
                                        action="{{ route('products.destroy', $product->id) }}"
                                        method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0; font-family: inherit; font-size: inherit;">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" style="text-align: center; padding: 20px; color: #64748b;">
                            Tidak ada produk yang cocok dengan pencarian atau filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
