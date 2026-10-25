@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="container" style="max-width: 720px;">
    <h1>Edit Data Produk</h1>

    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label for="sku" style="display: block; font-weight: bold; margin-bottom: 5px;">SKU / Kode Produk *</label>
                <input
                    type="text"
                    id="sku"
                    name="sku"
                    value="{{ old('sku', $product->sku) }}"
                    maxlength="50"
                    required
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('sku')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Produk *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    maxlength="150"
                    required
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('name')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
            </div>

            <!-- Preview & Ubah Foto Produk -->
            <div style="margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">Foto Produk Saat Ini</label>
                <div style="display: flex; gap: 15px; align-items: center;">
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;"
                    >
                    <div style="flex: 1;">
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            style="width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 6px; background: #fff;"
                        >
                        <small style="color: #64748b; display: block; margin-top: 4px;">Pilih file baru jika ingin mengganti foto produk.</small>
                    </div>
                </div>
                @error('image')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label for="category_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori *</label>
                    <select id="category_id" name="category_id" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="supplier_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Pemasok Utama</label>
                    <select id="supplier_id" name="supplier_id" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="">-- Tidak ada pemasok --</option>
                        @foreach ($suppliers as $supplier)
                            <option
                                value="{{ $supplier->id }}"
                                {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}
                            >
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label for="purchase_price" style="display: block; font-weight: bold; margin-bottom: 5px;">Harga Beli (Rp) *</label>
                    <input
                        type="number"
                        id="purchase_price"
                        name="purchase_price"
                        value="{{ old('purchase_price', $product->purchase_price) }}"
                        min="0"
                        step="0.01"
                        required
                        style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                    >
                    @error('purchase_price')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="selling_price" style="display: block; font-weight: bold; margin-bottom: 5px;">Harga Jual (Rp) *</label>
                    <input
                        type="number"
                        id="selling_price"
                        name="selling_price"
                        value="{{ old('selling_price', $product->selling_price ?: $product->price) }}"
                        min="0"
                        step="0.01"
                        required
                        style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                    >
                    @error('selling_price')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                <div>
                    <label for="stock" style="display: block; font-weight: bold; margin-bottom: 5px;">Jumlah Stok *</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', $product->stock) }}"
                        min="0"
                        required
                        style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                    >
                    @error('stock')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="minimum_stock" style="display: block; font-weight: bold; margin-bottom: 5px;">Batas Minimum Stok *</label>
                    <input
                        type="number"
                        id="minimum_stock"
                        name="minimum_stock"
                        value="{{ old('minimum_stock', $product->minimum_stock) }}"
                        min="0"
                        required
                        style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                    >
                    @error('minimum_stock')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal / Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
