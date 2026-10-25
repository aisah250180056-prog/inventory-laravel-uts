@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="container" style="max-width: 850px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Detail Produk: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('products.edit', $product->id) }}" class="btn">Edit Produk</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 25px; display: grid; grid-template-columns: 240px 1fr; gap: 25px;">
        <!-- Foto Produk -->
        <div>
            <div style="width: 100%; height: 240px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; background: #f8fafc;">
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    style="width: 100%; height: 100%; object-fit: cover;"
                    onerror="this.src='https://placehold.co/300x300/e2e8f0/1e293b?text=Foto+Produk'"
                >
            </div>
            @if($product->image)
                <small style="color: #64748b; display: block; text-align: center; margin-top: 6px;">Foto Tersimpan</small>
            @endif
        </div>

        <!-- Tabel Informasi Produk -->
        <div>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <tr>
                    <th width="35%" style="background: #f8fafc; text-align: left;">SKU</th>
                    <td><strong>{{ $product->sku ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Nama Produk</th>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Kategori</th>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Pemasok Utama</th>
                    <td>{{ $product->supplier?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Harga Beli</th>
                    <td>Rp {{ number_format((float) ($product->purchase_price ?: 0), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Harga Jual</th>
                    <td>Rp {{ number_format((float) ($product->selling_price ?: $product->price), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Stok Tersedia</th>
                    <td>
                        <strong>{{ $product->stock }} unit</strong>
                        @if($product->isLowStock())
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px;">
                                Stok Menipis
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="background: #f8fafc; text-align: left;">Batas Minimum Stok</th>
                    <td>{{ $product->minimum_stock }} unit</td>
                </tr>
            </table>
        </div>
    </div>

    <h2>Riwayat Mutasi Stok Terbaru</h2>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product->stockMovements as $m)
                <tr>
                    <td>{{ $m->created_at?->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($m->type === 'IN')
                            <span style="color: #166534; font-weight: bold;">+ MASUK</span>
                        @else
                            <span style="color: #dc2626; font-weight: bold;">- KELUAR</span>
                        @endif
                    </td>
                    <td>{{ $m->quantity }} unit</td>
                    <td>{{ $m->note ?? '-' }}</td>
                    <td>{{ $m->user?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b;">Belum ada riwayat pergerakan stok untuk produk ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
