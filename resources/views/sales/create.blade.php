@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Penjualan</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="sold_at" class="form-label">Tanggal Penjualan</label>
            <input
                type="datetime-local"
                id="sold_at"
                name="sold_at"
                class="form-control"
                value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}"
            >
        </div>

        <h5>Produk yang Dijual</h5>

        <div class="mb-3">
            <label for="product_id" class="form-label">Pilih Produk</label>
            <select
                id="product_id"
                name="items[0][product_id]"
                class="form-select"
                required
            >
                <option value="">-- Pilih Produk --</option>
                @foreach ($products as $product)
                    <option
                        value="{{ $product->id }}"
                        @selected(old('items.0.product_id') == $product->id)
                    >
                        {{ $product->name }}
                        (Stok: {{ $product->stock }})
                        - Rp {{ number_format((float) ($product->selling_price ?: $product->price), 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah</label>
            <input
                type="number"
                id="quantity"
                name="items[0][quantity]"
                class="form-control"
                min="1"
                value="{{ old('items.0.quantity', 1) }}"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Simpan Penjualan
        </button>

        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </form>
</div>
@endsection
