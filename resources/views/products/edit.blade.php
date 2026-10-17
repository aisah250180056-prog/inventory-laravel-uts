@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="sku">SKU / Kode Produk</label>
            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" maxlength="50" required>
            @error('sku')<p>{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="name">Nama Produk</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $product->name) }}"
                required
            >
            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id" required>
                <option value="">Pilih Kategori</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="supplier_id">Pemasok</label>
            <select id="supplier_id" name="supplier_id">
                <option value="">Tidak ada pemasok</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')<p>{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="price">Harga</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                step="0.01"
                required
            >
            @error('price')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock">Stok</label>
            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock', $product->stock) }}"
                min="0"
                required
            >
            @error('stock')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('products.index') }}">Kembali</a>
    </form>
</div>
@endsection
