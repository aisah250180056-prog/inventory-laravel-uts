@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Penjualan</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            + Tambah Penjualan
        </a>
    </p>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sales->firstItem() + $loop->index }}</td>
                        <td>{{ $sale->sale_number }}</td>
                        <td>{{ $sale->sold_at?->format('d-m-Y H:i') }}</td>
                        <td>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($sale->status) }}</td>
                        <td>{{ $sale->user?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada transaksi penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $sales->links() }}
</div>
@endsection
