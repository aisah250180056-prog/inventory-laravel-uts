@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container">

    <h1>Daftar Produk</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <a href="{{ route('products.create') }}">+ Tambah Produk</a>

    <form method="GET" action="{{ route('products.index') }}">
        <input
            type="text"
            name="search"
            placeholder="Cari nama produk..."
            value="{{ request('search') }}"
        >

        <select name="category_id">
            <option value="">Semua Kategori</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Cari / Filter</button>
        <a href="{{ route('products.index') }}">Reset</a>
    </form>

    <br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Pemasok</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $products->firstItem() + $loop->index }}</td>
                    <td>{{ $product->sku ?? '-' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->supplier?->name ?? '-' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}">Detail</a>
                        <a href="{{ route('products.edit', $product->id) }}">Edit</a>

                        <form
                            action="{{ route('products.destroy', $product->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Produk tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    {{ $products->links() }}

</div>
@endsection
