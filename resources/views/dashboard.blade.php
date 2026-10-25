@extends('layouts.app')

@section('title', 'Dashboard - Inventory Management System')

@section('content')
<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Dashboard Ringkasan Inventaris</h1>
        <div>
            <a href="{{ route('sales.create') }}" class="btn">+ Transaksi Penjualan</a>
            <a href="{{ route('purchases.create') }}" class="btn btn-secondary">+ Transaksi Pembelian</a>
        </div>
    </div>

    <!-- Metrik Statistik Utama -->
    <div class="stats">
        <section>
            <small>Total Produk Master</small>
            <h2>{{ number_format($stats['products']) }}</h2>
        </section>
        <section>
            <small>Total Transaksi</small>
            <h2>{{ number_format($stats['total_transactions']) }}</h2>
            <small style="color: #64748b;">{{ $stats['sales_count'] }} Penjualan | {{ $stats['purchases_count'] }} Pembelian</small>
        </section>
        <section>
            <small>Penjualan Hari Ini</small>
            <h2>Rp {{ number_format($stats['sales_today'], 0, ',', '.') }}</h2>
        </section>
        <section>
            <small>Penjualan Bulan Ini</small>
            <h2>Rp {{ number_format($stats['sales_this_month'], 0, ',', '.') }}</h2>
        </section>
        <section>
            <small>Total Omset Penjualan</small>
            <h2>Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h2>
        </section>
        <section style="{{ $stats['low_stock_count'] > 0 ? 'background-color: #fef2f2; border: 1px solid #f87171;' : '' }}">
            <small style="{{ $stats['low_stock_count'] > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                Produk Stok Menipis (<= Min)
            </small>
            <h2 style="{{ $stats['low_stock_count'] > 0 ? 'color: #dc2626;' : '' }}">
                {{ $stats['low_stock_count'] }}
            </h2>
            @if($stats['low_stock_count'] > 0)
                <small style="color: #dc2626;">Perlu restock segera!</small>
            @endif
        </section>
    </div>

    <!-- Produk Stok Menipis -->
    <section style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Daftar Produk Stok Rendah (≤ Minimum Stock)</h2>
            <a href="{{ route('products.index', ['stock_status' => 'low']) }}" style="font-size: 13px;">Lihat Semua Produk Menipis &rarr;</a>
        </div>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok Saat Ini</th>
                    <th>Batas Minimum</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStock as $product)
                    <tr style="background-color: #fff1f2;">
                        <td><strong>{{ $product->sku ?? '-' }}</strong></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '-' }}</td>
                        <td><span style="color: #dc2626; font-weight: bold;">{{ $product->stock }} unit</span></td>
                        <td>{{ $product->minimum_stock }} unit</td>
                        <td>
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                Stok Kritis
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('purchases.create') }}" class="btn" style="padding: 4px 10px; font-size: 12px;">Restock / Beli</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #166534; padding: 15px;">
                            ✓ Seluruh stok produk saat ini berada di atas batas minimum persediaan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <!-- Dua Kolom: Penjualan Terbaru & Mutasi Stok Terbaru -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
        <!-- Penjualan Terbaru -->
        <section>
            <h2>Penjualan Terbaru</h2>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}">
                                    <strong>{{ $sale->sale_number }}</strong>
                                </a>
                            </td>
                            <td>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
                            <td>
                                @if($sale->status === 'completed')
                                    <span style="color: #166534; font-weight: bold;">Selesai</span>
                                @else
                                    <span style="color: #dc2626; font-weight: bold;">Dibatalkan</span>
                                @endif
                            </td>
                            <td>{{ $sale->sold_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b;">Belum ada penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Mutasi Stok Terbaru -->
        <section>
            <h2>Mutasi Stok Terbaru</h2>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $m)
                        <tr>
                            <td>{{ $m->product?->name ?? '-' }}</td>
                            <td>
                                @if($m->type === 'IN')
                                    <span style="color: #166534; font-weight: bold;">+ MASUK</span>
                                @else
                                    <span style="color: #dc2626; font-weight: bold;">- KELUAR</span>
                                @endif
                            </td>
                            <td><strong>{{ $m->quantity }}</strong> unit</td>
                            <td>{{ $m->user?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b;">Belum ada mutasi stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>
</main>
@endsection
