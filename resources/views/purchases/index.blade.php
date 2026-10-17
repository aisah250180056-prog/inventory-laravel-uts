@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@section('content')
<div class="container">
    <h1>Transaksi Pembelian</h1>

    <p>
        <a href="{{ route('purchases.create') }}">+ Tambah Pembelian</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Pembelian</th>
                <th>Pemasok</th>
                <th>Pengguna</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($purchases as $purchase)
                <tr>
                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                    <td>{{ $purchase->purchase_number }}</td>
                    <td>{{ $purchase->supplier?->name ?? '-' }}</td>
                    <td>{{ $purchase->user?->name ?? '-' }}</td>
                    <td>Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($purchase->status) }}</td>
                    <td>{{ $purchase->purchased_at?->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Belum ada transaksi pembelian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>
    {{ $purchases->links() }}
</div>
@endsection
