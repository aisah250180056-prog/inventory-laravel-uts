@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="container">
    <h1>Detail Produk</h1>

    <p>
        <strong>Nama Produk:</strong>
        {{ $product->name }}
    </p>

    <p>
        <strong>Kategori:</strong>
        {{ $product->category->name }}
    </p>

    <p>
        <strong>Harga:</strong>
        Rp {{ number_format($product->price, 0, ',', '.') }}
    </p>

    <p>
        <strong>Stok:</strong>
        {{ $product->stock }}
    </p>

    <a href="{{ route('products.edit', $product->id) }}">
        Edit Produk
    </a>

    |

    <a href="{{ route('products.index') }}">
        Kembali ke Daftar
    </a>
</div>
@endsection
