@extends('layouts.app')

@section('title', 'Nota Pesanan - ' . $sale->sale_number)

@section('content')
<main class="container" style="max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Bukti Nota Pesanan</h1>
        <a href="{{ route('customer.orders') }}" class="btn btn-secondary">Kembali ke Daftar Pesanan</a>
    </div>

    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; color: #1e293b;">{{ $sale->sale_number }}</h2>
                <p style="margin: 5px 0 0; color: #64748b;">
                    Pemesan: <strong>{{ $sale->user?->name }} ({{ $sale->user?->email }})</strong>
                </p>
                <p style="margin: 3px 0 0; color: #64748b;">
                    Waktu Transaksi: <strong>{{ $sale->sold_at?->format('d/m/Y H:i') }}</strong>
                </p>
            </div>
            <div>
                @if($sale->status === 'completed')
                    <span style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                        ✓ Pembelian Berhasil
                    </span>
                @else
                    <span style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                        ✗ Pesanan Dibatalkan
                    </span>
                @endif
            </div>
        </div>

        <h3>Rincian Pembelian Produk</h3>
        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="margin-top: 15px;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $item->product?->name ?? 'Produk Dihapus' }}</strong></td>
                        <td>{{ $item->product?->category?->name ?? '-' }}</td>
                        <td style="text-align: center;"><strong>{{ $item->quantity }}</strong> unit</td>
                        <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f1f5f9; font-size: 16px;">
                    <th colspan="5" style="text-align: right; padding: 12px;">Total Pembayaran:</th>
                    <th style="padding: 12px; color: #2563eb;">
                        Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 25px; text-align: right;">
            <button type="button" class="btn btn-secondary" onclick="window.print()">Cetak Nota</button>
            <a href="{{ route('customer.order') }}" class="btn">Pesan Produk Lain</a>
        </div>
    </div>
</main>
@endsection
