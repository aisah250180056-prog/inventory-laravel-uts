@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Riwayat Transaksi Pembelian (Stock-In)</h1>
        <a href="{{ route('purchases.create') }}" class="btn">+ Tambah Pembelian</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('purchases.index') }}" style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 180px;">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari Nomor Pembelian (PO-...)"
                    value="{{ request('search') }}"
                    style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div style="min-width: 150px;">
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    <option value="">-- Semua Status --</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div>
                <input
                    type="date"
                    name="start_date"
                    value="{{ request('start_date') }}"
                    title="Dari Tanggal"
                    style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div>
                <input
                    type="date"
                    name="end_date"
                    value="{{ request('end_date') }}"
                    title="Sampai Tanggal"
                    style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div>
                <button type="submit" class="btn">Filter</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div style="overflow-x: auto;">
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Pembelian</th>
                    <th>Pemasok</th>
                    <th>Pengguna / Petugas</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchases->firstItem() + $loop->index }}</td>
                        <td>
                            <a href="{{ route('purchases.show', $purchase->id) }}" style="font-weight: bold;">
                                {{ $purchase->purchase_number }}
                            </a>
                        </td>
                        <td>{{ $purchase->supplier?->name ?? '-' }}</td>
                        <td>{{ $purchase->user?->name ?? '-' }}</td>
                        <td><strong>Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($purchase->status === 'completed')
                                <span style="background-color: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Selesai
                                </span>
                            @else
                                <span style="background-color: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Batal
                                </span>
                            @endif
                        </td>
                        <td>{{ $purchase->purchased_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>
                            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #64748b; padding: 20px;">
                            Belum ada riwayat transaksi pembelian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $purchases->links() }}
    </div>
</div>
@endsection
