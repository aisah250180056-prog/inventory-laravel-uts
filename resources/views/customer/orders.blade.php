@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h1>Riwayat Pesanan Saya</h1>
        <a href="{{ route('customer.order') }}" class="btn">+ Buat Pesanan Baru</a>
    </div>

    <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto;">
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr style="background: #f8fafc;">
                    <th>No</th>
                    <th>Nomor Pesanan / Nota</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $idx => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $idx }}</td>
                        <td><strong>{{ $order->sale_number }}</strong></td>
                        <td>{{ $order->sold_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td><strong>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($order->status === 'completed')
                                <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    ✓ Selesai
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    ✗ Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 4px 12px; font-size: 12px;">
                                Lihat Bukti Nota
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 25px; color: #64748b;">
                            Anda belum pernah membuat pesanan. <a href="{{ route('customer.order') }}">Mulai belanja sekarang</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
</main>
@endsection
