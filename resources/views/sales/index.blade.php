@extends('layouts.app')

@section('title', 'Data Penjualan')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Riwayat Transaksi Penjualan (Stock-Out)</h1>
        <a href="{{ route('sales.create') }}" class="btn">+ Tambah Penjualan</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('sales.index') }}" style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 180px;">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari Nomor Transaksi (SO-...)"
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
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <div style="overflow-x: auto;">
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $loop->index }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" style="font-weight: bold;">
                                {{ $sale->sale_number }}
                            </a>
                        </td>
                        <td>{{ $sale->sold_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td><strong>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($sale->status === 'completed')
                                <span style="background-color: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Selesai
                                </span>
                            @else
                                <span style="background-color: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                                    Batal
                                </span>
                            @endif
                        </td>
                        <td>{{ $sale->user?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">
                            Belum ada riwayat transaksi penjualan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $sales->links() }}
    </div>
</div>
@endsection
