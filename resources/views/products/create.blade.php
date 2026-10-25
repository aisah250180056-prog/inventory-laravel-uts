@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container" style="max-width: 720px;">
    <h1>Tambah Produk Baru</h1>

    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 15px;">
                <label for="sku" style="display: block; font-weight: bold; margin-bottom: 5px;">SKU / Kode Produk (Unik) *</label>
                <input
                    type="text"
                    id="sku"
                    name="sku"
                    value="{{ old('sku') }}"
                    placeholder="Contoh: SBK-001"
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
                    value="{{ old('name') }}"
                    placeholder="Contoh: Beras Premium Ramos 5kg"
                    maxlength="150"
                    required
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('name')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label for="image" style="display: block; font-weight: bold; margin-bottom: 5px;">Foto Produk (Opsional)</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                <small style="color: #64748b;">Format yang didukung: JPG, PNG, WEBP (Maks. 2MB)</small>
                @error('image')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label for="category_id" style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori *</label>
                    <select id="category_id" name="category_id" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                        value="{{ old('purchase_price', 0) }}"
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
                        value="{{ old('selling_price', 0) }}"
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
                    <label for="stock" style="display: block; font-weight: bold; margin-bottom: 5px;">Stok Awal *</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', 0) }}"
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
                        value="{{ old('minimum_stock', 5) }}"
                        min="0"
                        required
                        style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                    >
                    <small style="color: #64748b;">Peringatan jika sisa stok mencapai nilai ini.</small>
                    @error('minimum_stock')<p style="color: #dc2626; margin-top: 4px; font-size: 13px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Simpan Produk</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal / Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
