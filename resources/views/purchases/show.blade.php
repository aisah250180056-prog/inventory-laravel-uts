@extends('layouts.app')

@section('title', 'Detail Pembelian - ' . $purchase->purchase_number)

@section('content')
<div class="container" style="max-width: 850px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Detail Transaksi Pembelian</h1>
        <div>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
        </div>
    </div>

    <!-- Header Transaksi -->
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
            <div>
                <h2 style="margin: 0; color: #1e293b;">{{ $purchase->purchase_number }}</h2>
                <p style="color: #64748b; margin: 4px 0 0;">
                    Pemasok: <strong>{{ $purchase->supplier?->name ?? 'Pemasok Dihapus' }}</strong>
                </p>
                <p style="color: #64748b; margin: 2px 0 0;">
                    Tanggal Pembelian: <strong>{{ $purchase->purchased_at?->format('d/m/Y H:i') ?? '-' }}</strong>
                </p>
                <p style="color: #64748b; margin: 2px 0 0;">
                    Petugas Penerima: <strong>{{ $purchase->user?->name ?? 'Sistem' }}</strong>
                </p>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 13px; color: #64748b; display: block; margin-bottom: 4px;">Status Transaksi:</span>
                @if($purchase->status === 'completed')
                    <span style="background-color: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                        ✓ Berhasil / Selesai
                    </span>
                @else
                    <span style="background-color: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                        ✗ Dibatalkan (Stok Ditarik)
                    </span>
                @endif
            </div>
        </div>

        <!-- Tabel Detail Barang -->
        <h3>Rincian Barang yang Diterima</h3>
        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="margin-top: 10px;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Jumlah (Qty)</th>
                    <th>Harga Beli Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $item->product?->sku ?? '-' }}</strong></td>
                        <td>{{ $item->product?->name ?? 'Produk Dihapus' }}</td>
                        <td>{{ $item->product?->category?->name ?? '-' }}</td>
                        <td style="text-align: center;"><strong>{{ $item->quantity }}</strong></td>
                        <td>Rp {{ number_format((float) $item->unit_cost, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f1f5f9; font-size: 16px;">
                    <th colspan="6" style="text-align: right; padding: 12px;">Total Pembelian:</th>
                    <th style="padding: 12px; color: #2563eb;">
                        Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <!-- Tindakan Pembatalan (Admin Only) -->
        @if(auth()->user()->isAdmin() && $purchase->status === 'completed')
            <div style="margin-top: 30px; padding: 15px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 6px;">
                <h4 style="color: #991b1b; margin: 0 0 8px;">Aksi Khusus Administrator: Pembatalan Transaksi</h4>
                <p style="color: #7f1d1d; font-size: 13px; margin-bottom: 12px;">
                    Sesuai aturan bisnis konsistensi stok, pembatalan pembelian akan <strong>mengurangi jumlah stok barang</strong> yang sebelumnya ditambahkan (hanya jika sisa stok mencukupi dan belum terjual).
                </p>
                <form action="{{ route('purchases.cancel', $purchase->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi pembelian ini? Stok barang akan dikurangi dari inventaris.')">
                    @csrf
                    <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        Batalkan Transaksi Pembelian & Tarik Stok
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
